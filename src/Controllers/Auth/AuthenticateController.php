<?php

namespace App\Controllers\Auth;

use _PHPStan_d279f388f\Nette\Neon\Exception;
use App\Application\Actions\Auth\SyncTokenAction;
use App\Controllers\BaseController;
use App\Models\User;
use App\Services\JWTService\JWTService;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class AuthenticateController extends BaseController
{
    /**
     * @throws Exception
     */
    public function login(Request $request, Response $response)
    {
        $jsonResponse = [];
        $formBody = $request->getParsedBody();
        $tokenAction = new SyncTokenAction();
        if (!isset($formBody['email']) || !isset($formBody['email'])) {
            throw new Exception('The field "email" & "password" required.', 400);
        }
        $user = User::where('email', $formBody['email'])->first();
        if (!($user && password_verify($formBody['password'], $user->password))) {
            throw new Exception('Email or password incorrect!');
        }

        if ($user->hasValidToken()) {
            $jsonResponse['token'] = $user->tokens;
            $tokenAction->attach($user, $jsonResponse['token']);
            return $this->successResponse($response, $jsonResponse, 'Successfully authenticated user');
        }
        $token = JWTService::make()
            ->setPayload($user->id, $request->getUri()
            )->createToken();

        $jsonResponse['token'] = $token;
        $jsonResponse['message'] = 'Successfully authenticated user';

        $user->tokens()->create(['token_title' => 'user login token', 'token' => $token]);
        $tokenAction->attach($user, $token);


        return $this->successResponse($response, $jsonResponse, 'Successfully authenticated user', 201);
    }

    public function logout(Request $request, Response $response): Response
    {
        try {
            $tokenAction = new SyncTokenAction();
            $token = $request->getHeaders()['token'];
            if (!$token) {
                throw new Exception('Invalid Operation!');
            }
            $tokenAction->detach($token);
            return $this->successResponse($response, [], 'User logged out successfully');
        } catch (Exception $exception) {
            return $this->errorResponse($response, $exception->getMessage());
        }
    }
}