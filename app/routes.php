<?php

declare(strict_types=1);

use App\Application\Middleware\JWTMiddleware;
use App\Controllers\Auth\AuthenticateController;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Slim\Psr7\Response;

return function (App $app) {
//    $app->options('/{routes:.*}', function (Request $request, Response $response) {
//        // CORS Pre-Flight OPTIONS Request Handler
//        return $response;
//    });
//
//    $app->get('/', function (Request $request, Response $response) {
//        $response->getBody()->write('Hello world!');
//        return $response;
//    });
//
//    $app->group('/users', function (Group $group) {
//        $group->get('', ListUsersAction::class);
//        $group->get('/{id}', ViewUserAction::class);
//    });

    $app->group('/api/v1', function (Group $group) {
        $group->post('/login', AuthenticateController::class . ':login');
        $group->post('/logout', AuthenticateController::class . ':logout');

        $group->get('/test', function (\Slim\Psr7\Request $request, Response $response) {
            $response->getBody()->write('Accepted');
            return $response->withHeader("Content-Type", "application/json");
        })->add(JWTMiddleware::class);
    });

};
