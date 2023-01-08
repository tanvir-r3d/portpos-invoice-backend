<?php

declare(strict_types=1);

use App\Application\Middleware\JWTMiddleware;
use App\Controllers\Auth\AuthenticateController;
use App\Controllers\Order\OrderController;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->group('/api/v1', function (Group $group) {
        $group->post('/login', AuthenticateController::class . ':login');
        $group->post('/logout', AuthenticateController::class . ':logout');

        $group->patch('/orders/status/{id}/{status}', OrderController::class . ':updateStatus')->add(JWTMiddleware::class);
        $group->get('/orders/ipn/{id}', OrderController::class . ':fetchIpn')->add(JWTMiddleware::class);
        $group->post('/orders', OrderController::class . ':store')->add(JWTMiddleware::class);
        $group->get('/orders', OrderController::class . ':index')->add(JWTMiddleware::class);
    });
};
