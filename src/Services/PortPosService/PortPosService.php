<?php

namespace App\Services\PortPosService;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

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

    /**
     * @return static
     */
    public static function init(): self
    {
        return new static();
    }

    /**
     * @return Client
     */
    private function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $options
     * @return ResponseInterface
     * @throws GuzzleException
     */
    private function getRequest(string $method, string $url, array $options = []): ResponseInterface
    {
        return $this->getClient()->request($method, $url, $options);
    }

    /**
     * @return string
     */
    private function getAuthKey(): string
    {
        return 'Bearer ' . base64_encode($_ENV['APP_KEY'] . ":" . md5($_ENV['SECRET_KEY'] . time()));
    }

    /**
     * @param $body
     * @return $this
     */
    public function setBody($body): self
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return mixed
     */
    private function getBody()
    {
        return $this->body;
    }

    /**
     * @return string|ResponseInterface
     * @throws GuzzleException
     */
    public function generateInvoice(): string|ResponseInterface
    {
        try {
            return $this->getRequest('POST', 'payment/v2/invoice', [
                'json' => $this->getBody(),
                'allow_redirects' => true
            ]);
        } catch (BadResponseException $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * @param $invoiceId
     * @param $amount
     * @return string|ResponseInterface
     * @throws GuzzleException
     */
    public function getIPN($invoiceId, $amount): string|ResponseInterface
    {
        try {
            return $this->getRequest('GET', "payment/v2/invoice/ipn/$invoiceId/$amount");
        } catch (BadResponseException $exception) {
            return $exception->getMessage();
        }
    }
}
