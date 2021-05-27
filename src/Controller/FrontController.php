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

/**
 * @Route("/api/v1/products/recommended")
 */
class FrontController extends AbstractController
{
    /**
     * @Route("/{city}", methods="GET")
     */
    public function getRecommendedProductsAction(Request $request, Client $client, SelectProductCategoryService $productCategoryService, ProductModel $productModel): JsonResponse
    {
        $responseFromClient = $client->fetchDataFromClient($request->get('city'));
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
            'city' => $request->get('city'),
            'recommendations' => $responseDataArray
        ];


        return $this->json($response, Response::HTTP_OK, [], [
            ObjectNormalizer::IGNORED_ATTRIBUTES => ['id', 'productCategory']
        ]);
    }
}
