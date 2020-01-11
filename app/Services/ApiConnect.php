<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Exceptions\GameApiException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;

class ApiConnect
{
    // const API_URI = config('services.pw_api.')

    public function __construct()
    {
        $this->url = 'http://127.0.0.1/';
    }

    /**
     * Send a request to Game's API.25230172019.
     *
     * @param array $params Request data, such as the service name, user_id, etc.
     *
     * @return void
     * @throws GameApiException
     */
    public function dispatch(array $params = [])
    {
        $service = $params['service'] ?? null;
        $data = $params['data'] ?? null;

        try {
            $client = new Client();
            $request = $client->request('POST', "http://127.0.0.1/$service", [
                'headers' => [
                    'ApiToken' => config('services.pw_api.token'),
                ],
                'timeout' => 5,
                'connect_timeout' => 5,
                'form_params' => ['data' => json_encode($data)],
            ]);

            return json_decode($request->getBody(), true);
        } catch (ConnectException $e) {
            throw new GameApiException($e->getMessage(), $e->getCode());
        } catch (RequestException $e) {
            throw new GameApiException($e->getMessage(), $e->getCode());
        }
    }
}
