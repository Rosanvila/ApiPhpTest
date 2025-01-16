<?php

namespace App\Service;

class CurlClient
{
    public function sendRequest(string $url, string $method, array $data = []): array
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if (in_array($method, ['POST', 'PUT']) && !empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen(json_encode($data)),
            ]);
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new \RuntimeException("cURL Error: $error");
        }

        curl_close($ch);

        return [
            'status' => $httpCode,
            'response' => json_decode($response, true),
        ];
    }
}
