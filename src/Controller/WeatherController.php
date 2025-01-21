<?php

namespace App\Controller;

use App\Service\CurlClient;
use DateTime;
use Exception;
use Psr\Http\Message\ServerRequestInterface;
use GuzzleHttp\Psr7\Response;

class WeatherController
{
    private CurlClient $curlClient;

    public function __construct()
    {
        $apiKey = require_once __DIR__ . '/../config/openWeatherKey.php';
        if (!is_string($apiKey) || empty($apiKey)) {
            throw new Exception("Clé API invalide");
        }

        $this->curlClient = new CurlClient($apiKey);
    }

    public function getForecast(string $city): ?array
    {
        try {
            $data = $this->curlClient->callApi("forecast?q={$city}");
            if (!$data) {
                error_log("Aucune donnée retournée par l'API");
                return null;
            }
            $result = [
                'city' => $data['city']['name'],
                'forecast' => []
            ];
            foreach ($data['list'] as $day) {
                $result['forecast'][] = [
                    'temp' => $day['main']['temp'],
                    'description' => $day['weather'][0]['description'],
                    'date' => new DateTime('@' . $day['dt'])
                ];
            }
            return $result;
        } catch (Exception $e) {
            error_log("Exception: " . $e->getMessage());
            return null;
        }
    }

    public function index(ServerRequestInterface $request): Response
    {
        $city = $request->getQueryParams()['city'] ?? 'Paris';
        $forecast = $this->getForecast($city);

        if ($forecast === null) {
            error_log("Prévisions nulles pour la ville : " . $city);
        }

        ob_start();
        $pageContent = __DIR__ . "/../../templates/meteo.html.php";
        include $pageContent;
        $content = ob_get_clean();

        return new Response(200, ['Content-Type' => 'text/html'], $content);
    }
}
