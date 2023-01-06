<?php

declare(strict_types=1);

namespace App\Application\Middleware;

use App\Services\JWTService\JWTService;
use DateTime;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class JWTMiddleware implements Middleware
{
    /**
     * {@inheritdoc}
     * @throws Exception
     */
    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        $response = new Response();
        try {
            if ($request->hasHeader("Authorization")) {
                $header = $request->getHeader("Authorization");
                if (!empty($header)) {
                    $bearer = trim($header[0]);
                    preg_match("/Bearer\s(\S+)/", $bearer, $matches);
                    $token = $matches[1];
                    $decodedToken = JWTService::make()->decodeToken($token);
                    print_r($decodedToken);
                    exit();
                    $now = (new DateTime("now", new \DateTimeZone("Asia/Dhaka")))
                        ->format("Y-m-d H:i:s");

                    if ($decodedToken['expiresAt'] < $now) {
                        throw new Exception('Token Expired');
                    }
                } else {
                    throw new Exception('AUnauthorized Request.');
                }
            } else {
                throw new Exception('BUnauthorized Request.');
            }

            return $handler->handle($request);
        } catch (Exception $exception) {
            $response->getBody()->write($exception->getMessage());
            return $response->withHeader("Content-Type", "application/json")->withStatus(401);
        }
    }

}