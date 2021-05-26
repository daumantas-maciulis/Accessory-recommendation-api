<?php
declare(strict_types=1);

namespace App\Controller;

use App\Client\LHMT\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function getRecommendedProductsAction(Request $request, Client $client)
    {

        $response = $client->fetchDataFromClient($request->get('city'));

        dump($response);
    }


}