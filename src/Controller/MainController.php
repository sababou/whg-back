<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Game;

use App\Entity\Brand;
use App\Entity\BrandGame;
use App\Entity\GameBrandBlock;
use App\Entity\GameCountryBlock;
use App\Entity\Category;
use App\Entity\Country;

#[Route('/api')]
class MainController extends AbstractController
{
    #[Route('/game_list', name: 'game_list')]
    public function gameList(Request $request): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();

        // Treating request parameters
        $brandId = $request->query->has('brand_id')?$request->query->get('brand_id'):null;
        $brand = $brandId ? $em->getRepository(Brand::class)->find($brandId) : null;

        $country = $request->query->has('country')?$request->query->get('country'):null;

        $category = $request->query->has('category')?$request->query->get('category'):null;

        $page = $request->query->has('p')?$request->query->get('p'):0;

        // Retrieving data

        $games = $this->getFilteredGameList($category, $brand, $country, $page);


        // Parsing data to array
        $games4JSON = array();

        foreach($games as $game){
          $gameArray = $game->buildArray();
          $gameArray['hot'] = $em->getRepository(BrandGame::class)->isHot($game->getLaunchcode());
          $gameArray['new'] = $em->getRepository(BrandGame::class)->isNew($game->getLaunchcode());
          $games4JSON[] = $gameArray;
        }

        // Return JSON Response
        $responseData = array(
          "status" => "OK",
          "games" => $games4JSON
        );

        return new JsonResponse($responseData);
    }


    #[Route('/brand_list', name: 'brand_list')]
    public function brandList(): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();

        $brandGames = $em->getRepository(BrandGame::class)->findPopulated();

        $brandsJSON = array();

        foreach($brandGames as $brandGame){
          $brandsJSON[] = $brandGame->getBrand()->buildArray();
        }

        $responseData = array(
          "status" => "OK",
          "brands" => $brandsJSON
        );

        return new JsonResponse($responseData);
    }


    #[Route('/category_list', name: 'categories')]
    public function categories(): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();

        $categoryList = $em->getRepository(BrandGame::class)->getCategoryList();

        $categories = $em->getRepository(Category::class)->getForCategory($categoryList);

        $categoriesJSON = array();
        foreach($categories as $category){
          $categoriesJSON[] = $category->buildArray();
        }

        $responseData = array(
          "status" => "OK",
          "categories" => $categoriesJSON
        );

        return new JsonResponse($responseData);
    }


    #[Route('/country_list', name: 'country_list')]
    public function countryList(): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();

        $countries = $em->getRepository(Country::class)->findBy([], ["country" => "ASC"]);

        $countriesJSON = array();

        foreach($countries as $country){
          $countriesJSON[] = $country->buildArray();
        }

        $responseData = array(
          "status" => "OK",
          "countries" => $countriesJSON
        );

        return new JsonResponse($responseData);
    }




    ///////////////////////////////////////////////////////////////////////////
    //
    //    PRIVATE METHODS
    //
    //////////////////////////////////////////////////////////////////////////





    private function getFilteredGameList(?string $categoryName, ?Brand $brand, ?string $country, $page): array
    {
      $em = $this->getDoctrine()->getManager();

      // CASE : If no parameter is set
      if($categoryName == null
      && $brand == null
      && $country == null){
        return $em->getRepository(Game::class)->findBy([], [], 24, 24*$page);
      }


      // CASE : At least 1 parameter is set
      $blockedGamesLaunchcodeByBrand = null;
      $blockedGamesLaunchcodeByCountry = null;

      if($brand){
        $beforeBlockArray = $this->getLaunchcodesByBrand($brand);
        $blockedGamesLaunchcodeByBrand = $this->getBlockedLaunchcodesByBrand($brand);
      }else{
        $beforeBlockArray = $this->getAllLaunchcodes();
      }

      if($country){
        $allBrands = new Brand();
        $allBrands->setId(0);

        $blockedGamesLaunchcodeByCountry = $this->getBlockedLaunchcodesByCountry($allBrands, $country);

        if($brand){
          $blockedGamesLaunchcodeByCountry =
                    array_merge($blockedGamesLaunchcodeByCountry, $this->getBlockedLaunchcodesByCountry($brand, $country));
        }
      }

      // Get the non-blocked games launchcode of the brand and/or the country
      $launchcodes = $beforeBlockArray;

      $launchcodes = $brand ? array_diff($launchcodes, $blockedGamesLaunchcodeByBrand) : $launchcodes;


      $launchcodes = $country ? array_diff($launchcodes, $blockedGamesLaunchcodeByCountry) : $launchcodes;

      if($categoryName){
        $launchcodes = array_intersect($launchcodes, $this->getLaunchcodesByCategoryName($categoryName));
      }


      $games = $em->getRepository(Game::class)->getForLaunchcodeList($launchcodes, $page);

      return $games;

    }


    private function getLaunchcodesByCategoryName($categoryName){
      $em = $this->getDoctrine()->getManager();

      $categories = $em->getRepository(Category::class)->findByName($categoryName);

      $launchcodes = array();

      foreach($categories as $category){
        $brandGames = $em->getRepository(BrandGame::class)->findByCategory($category->getCategory());
        foreach($brandGames as $brandGame){
          $launchcodes[] = $brandGame->getLaunchcode();
        }
      }

      return $launchcodes;

    }



    private function getAllLaunchcodes(){
      $em = $this->getDoctrine()->getManager();

      $launchcodes = array();
      $games = $em->getRepository(Game::class)->findAll();
      foreach($games as $games){
        $launchcodes[] = $games->getLaunchcode();
      }

      return $launchcodes;
    }

    private function getLaunchcodesByBrand(Brand $brand){
      $em = $this->getDoctrine()->getManager();

      $launchcodes = array();
      $brandGames = $em->getRepository(BrandGame::class)->findByBrand($brand);
      foreach($brandGames as $brandGame){
        $launchcodes[] = $brandGame->getLaunchcode();
      }

      return $launchcodes;
    }

    private function getBlockedLaunchcodesByBrand(Brand $brand){
      $em = $this->getDoctrine()->getManager();

      $launchcodes = array();
      $gameBrandBlocks = $em->getRepository(GameBrandBlock::class)->findByBrand($brand);
      foreach($gameBrandBlocks as $gameBrandBlock){
        $launchcodes[] = $gameBrandBlock->getLaunchcode();
      }

      return $launchcodes;
    }

    private function getBlockedLaunchcodesByCountry(Brand $brand, string $country){
      $em = $this->getDoctrine()->getManager();

      $launchcodes = array();
      $gameCountryBlocks = $em->getRepository(GameCountryBlock::class)->findBy(["brand" => $brand , "country" => $country]);
      foreach($gameCountryBlocks as $gameCountryBlock){
        $launchcodes[] = $gameCountryBlock->getLaunchcode();
      }

      return $launchcodes;
    }


}
