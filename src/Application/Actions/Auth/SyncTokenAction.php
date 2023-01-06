<?php

namespace App\Application\Actions\Auth;

use _PHPStan_d279f388f\Nette\Neon\Exception;
use App\Models\JWTToken;
use App\Models\User;

class SyncTokenAction
{
    public function attach(User $user, $token): void
    {
        $user->update([
            'last_login_at' => date('Y-m-d H:i:s'),
        ]);

        $user->tokens()->where('token', $token)->update([
            'last_used_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * @throws Exception
     */
    public function detach($token): void
    {
        $jwt = JWTToken::query()->where('token', $token)->first();
        if (!$jwt) {
            throw new Exception('Invalid Token');
        }
        JWTToken::query()->where('token', $token)->delete();
    }
}