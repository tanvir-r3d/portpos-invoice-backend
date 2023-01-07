<?php

namespace App\Services\PortPosService;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class PortPosService
{
    private $client;
    private $body;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api-sandbox.portwallet.com',
            'headers' => [
                'Authorization' => $this->getAuthKey(),
            ],
        ]);
        return $this;
    }

    public static function init(): self
    {
        return new static();
    }

    private function getClient(): Client
    {
        return $this->client;
    }

    private function getAuthKey()
    {
        return 'Bearer ' . base64_encode($_ENV['APPKEY'] . ":" . md5($_ENV['SECRETKEY'] . time()));
    }

    public function setBody($body): self
    {
        $this->body = $body;
        return $this;
    }

    private function getBody()
    {
        return $this->body;
    }

    /**
     * @throws GuzzleException
     */
    public function generateInvoice()
    {
        try {
            $response = $this->getClient()->request('POST', 'payment/v2/invoice',
                [
                    'json' => $this->getBody(),
                    'allow_redirects' => true
                ]
            );
            return $response;
        } catch (BadResponseException $exception) {
            return $exception->getMessage();
        }
    }
}