<?php

declare(strict_types=1);

use App\Application\Middleware\JWTMiddleware;
use App\Controllers\Auth\AuthenticateController;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Slim\Psr7\Response;

return function (App $app) {
//     $app->add(new Tuupola\Middleware\CorsMiddleware);

    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        //     // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->group('/api/v1', function (Group $group) {
        $group->post('/login', AuthenticateController::class . ':login');
        $group->post('/logout', AuthenticateController::class . ':logout');

        $group->get('/test', function (\Slim\Psr7\Request $request, Response $response) {
            $response->getBody()->write('Accepted');
            return $response->withHeader("Content-Type", "application/json");
        })->add(JWTMiddleware::class);
    });

    $app->add(new Tuupola\Middleware\CorsMiddleware);

    $app->add(function ($request, $handler) {
        $response = $handler->handle($request);
        return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
    });
};
