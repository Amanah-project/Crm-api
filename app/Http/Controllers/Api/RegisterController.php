<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Actions\Auth\RegisterAction;

class RegisterController extends Controller
{
    public function store(RegisterRequest $request, RegisterAction $action)
    {
        $result = $action->handle($request->validated());

        return response()->json($result, 201);
    }
}
