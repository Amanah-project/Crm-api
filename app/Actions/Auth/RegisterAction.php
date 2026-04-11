<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Validation\ValidationException;

class RegisterAction
{
    /**
     * Handle user registration and create personal access token.
     * Returns token (immediate-login) and user.
     *
     * @param  array  $data
     * @return array
     */
    public function handle(array $data): array
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => $data['role'] ?? 'viewer',
        ]);

        if (! $user) {
            throw ValidationException::withMessages(['email' => ['Unable to create user']]);
        }

        $deviceName = $data['device_name'] ?? 'crm-client';
        $token = $user->createToken($deviceName)->plainTextToken;

        return [
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ];
    }
}
