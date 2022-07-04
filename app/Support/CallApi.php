<?php
/**
 * Created by PhpStorm.
 * User: caotu
 * Date: 10/1/2018
 * Time: 1:58 PM
 */

namespace App\Support;


use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class CallApi
{
    public static function sendRequest($method, $uri, $data = [], $type = 'form_params')
    {
        $type = strtolower($type);
        if ($type !== 'form_params' && $type !== 'json') {
            throw new \Exception('Invalid request type');
        }

        $requestOption = [
            $type => $data,
        ];

        if ($type === 'json') {
            $requestOption['Content-Type'] = 'application/json';
        }

        $headers = ['User-Agent' => 'GuzzleHttp/' . phpversion()];

        if(@$data['access_token']){
            $headers['Authorization'] = 'Bearer ' .$data['access_token'];
        }

        try {
            $client = new \GuzzleHttp\Client([
                'timeout' => 300,
                'curl' => array(CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false),
                'verify' => false,
                'headers' => $headers
            ]);

            $response = $client->request($method, $uri, $requestOption);

            return [
                'status_code' => $response->getStatusCode(),
                'headers' => $response->getHeaders(),
                'body' => $response->getBody()->getContents(),
            ];
        } catch (RequestException $e) {
            Log::error('Call API', [$e->getMessage()]);

            return [
                'status_code' => 500
            ];
        }


    }
}
