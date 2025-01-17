<?php 

namespace App\Helpers;

use GuzzleHttp\Psr7\Response;

class ResponseHelper extends Response
{
    public static function jsonResponse(array $data, int $status = 200, array $headers = [], int $encodingOptions = 0): Response
    {
        return new Response($status, array_merge($headers, ['Content-Type' => 'application/json']), json_encode($data, $encodingOptions));
    }
}