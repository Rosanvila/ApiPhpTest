<?php

namespace App\Service;

use Exception;

class CurlClient
{
    private string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function callApi(string $endpoint): array
    {
        $curl = curl_init();
        $certPath = __DIR__ . '/../config/cert.cer';

        if (!file_exists($certPath)) {
            throw new Exception("Certificat introuvable : $certPath");
        }

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CAINFO         => $certPath,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_URL            => "https://api.openweathermap.org/data/2.5/{$endpoint}&units=metric&lang=fr&appid={$this->apiKey}"
        ]);

        $data = curl_exec($curl);

        if ($data === false) {
            throw new Exception(curl_error($curl));
        }

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($httpCode !== 200) {
            throw new Exception("Erreur lors de la requête : code HTTP $httpCode");
        }

        $data = json_decode($data, true);

        if ($data === null) {
            throw new Exception("Erreur lors de la conversion du JSON");
        }

        curl_close($curl);
        return $data;
    }
}