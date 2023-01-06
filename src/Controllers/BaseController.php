<?php

namespace App\Controllers;

use Slim\Psr7\Response;

class BaseController
{
    /**
     * Success response send function
     *
     * @param Response $response
     * @param mixed $data Main data pass.
     * @param string|null $message Some message.
     * @param int $code Status code pass.
     * @return Response
     */
    protected function successResponse(Response $response, mixed $data, string $message = null, int $code = 200): Response
    {
        $response->getBody()->write(json_encode([
            'status' => 'Success',
            'message' => $message,
            'code' => $code,
            'data' => $data,
        ]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($code);
    }

    /**
     * Error response send function
     *
     * @param Response $response
     * @param string $message Some message.
     * @param int $code Status code pass.
     * @return Response
     */
    protected function errorResponse(Response $response, string $message, int $code = 500): Response
    {
        $response->getBody()->write(json_encode([
            'status' => 'Error',
            'message' => $message,
            'code' => $code,
            'data' => null,
        ]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($code);
    }
}