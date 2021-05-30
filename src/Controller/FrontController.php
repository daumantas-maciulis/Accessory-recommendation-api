<?php
declare(strict_types=1);

namespace App\Controller;

use App\Client\LHMT\Client;
use App\Model\ProductModel;
use App\Service\SelectProductCategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
            $item->expiresAfter(1);
            //todo change expiration back to 500
            $selectedCityForecast = json_decode($client->fetchDataFromClient($city), true);
            $selectedProductTypes = $productCategoryService->selectProductType($selectedCityForecast['forecastTimestamps']);
            $responseDataArray = [];
            foreach ($selectedProductTypes as $productType) {
                $selectedProducts = $productModel->findProductsByCategory($productType['weatherForecast']);
                if (!$selectedProducts) {
                    $selectedProducts = [
                        'error' => 'There are no Items in this category'
                    ];
                }
                $data = $this->createDaysResponseData($productType, $selectedProducts);

                array_push($responseDataArray, $data);
            }

            return $this->createFullResponseArray($city, $responseDataArray);
        });

        return $this->json($response, Response::HTTP_OK, [], [
            ObjectNormalizer::IGNORED_ATTRIBUTES => ['id', 'productCategory']
        ]);
    }

    private function createDaysResponseData(array $productType, array $products): array
    {
        return [
            'Weather_forecast' => $productType['weatherForecast'],
            'date' => $productType['date'],
            'products' => $products
        ];
    }

    private function createFullResponseArray(string $city, array $responseDataArray): array
    {
        return [
            'city' => ucfirst($city),
            'recommendations' => $responseDataArray,
            'additional information' => 'Weather for this information was taken from LHMT API. For more information: https://api.meteo.lt/'
        ];
    }
}

