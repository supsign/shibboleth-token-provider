<?php

namespace App\Http\Controllers;

use App\Services\TokenService;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    public function issue(TokenService $tokenService)
    {
       return $tokenService->issue(1255)->toString();
    }

    public function validateToken(TokenService $tokenService, Request $request)
    {
       return $tokenService->isValid($request->token);
    }
}
