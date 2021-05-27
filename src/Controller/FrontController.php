<?php
declare(strict_types=1);

namespace App\Controller;

use App\Client\LHMT\Client;
use App\Model\ProductModel;
use App\Service\SelectProductCategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * @Route("/api/v1/products/recommended")
 */
class FrontController extends AbstractController
{
    /**
     * @Route("/{city}", methods="GET")
     */
    public function getRecommendedProductsAction($city, Client $client, SelectProductCategoryService $productCategoryService, ProductModel $productModel, CacheInterface $accessoryRecomendationCache): JsonResponse
    {
        $response = $accessoryRecomendationCache->get($city, function (ItemInterface $item) use ($city, $client, $productCategoryService, $productModel) {
            $item->expiresAfter(500);
            $responseFromClient = $client->fetchDataFromClient($city);
            $selectedCityForecast = json_decode($responseFromClient, true);
            $selectedProductTypes = $productCategoryService->selectProductType($selectedCityForecast['forecastTimestamps']);
            $responseDataArray = [];
            foreach ($selectedProductTypes as $productType) {
                $selectedProducts = $productModel->findProductsByCategory($productType['weatherForecast']);
                $data = [
                    'Weather_forecast' => $productType['weatherForecast'],
                    'date' => $productType['date'],
                    'products' => $selectedProducts
                ];
                array_push($responseDataArray, $data);
            }

            $response = [
                'city' => ucfirst($city),
                'recommendations' => $responseDataArray,
                'additional information' => 'Weather for this information was taken from LHMT API. For more information: https://api.meteo.lt/'
            ];
            dump("not cached");
            return $response;
        });

        return $this->json($response, Response::HTTP_OK, [], [
            ObjectNormalizer::IGNORED_ATTRIBUTES => ['id', 'productCategory']
        ]);
    }
}

