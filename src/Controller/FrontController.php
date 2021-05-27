<?php
declare(strict_types=1);

namespace App\Controller;

use App\Client\LHMT\Client;
use App\Service\SelectProductTypeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1/products/recommended")
 */
class FrontController extends AbstractController
{
    /**
     * @Route("/{city}", methods="GET")
     */
    public function getRecommendedProductsAction(Request $request, Client $client, SelectProductTypeService $productType): JsonResponse
    {
        $responseFromClient = $client->fetchDataFromClient($request->get('city'));
        $selectedCityForecast = json_decode($responseFromClient, true);
        $productType->selectProductType($selectedCityForecast['forecastTimestamps']);

        return $this->json($selectedCityForecast['forecastTimestamps']);
    }


}