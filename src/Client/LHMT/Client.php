<?php
declare(strict_types=1);

namespace App\Client\LHMT;

use GuzzleHttp\Client as Guzzle;
use Symfony\Component\HttpFoundation\Request;

class Client
{
    public function fetchDataFromClient(string $cityName)
    {
        $client = new Guzzle([
            'base_uri' => 'https://api.meteo.lt',
        ]);

        $requestUrl = sprintf("/v1/places/%s/forecasts/long-term", $cityName);
        $responseFromClient = $client->request(Request::METHOD_GET, $requestUrl);

        return $responseFromClient->getBody()->getContents();
    }
}
