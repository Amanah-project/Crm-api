<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Actions\Auth\LoginAction;
use App\Http\Requests\Auth\RegisterRequest;
use App\Actions\Auth\RegisterAction;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only('destroy');
    }

    public function store(LoginRequest $request, LoginAction $action)
    {
        $result = $action->handle($request->validated());

        return response()->json($result, 200);
    }

    public function destroy(Request $request)
    {
        $user = $request->user();

        if ($user && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }

        return response()->noContent();
    }
}
