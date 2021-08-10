<?php

namespace App\Http\Controllers;

use App\Services\Shibboleth\ShibbolethService;
use App\Services\Token\Role;
use App\Services\Token\TokenConfig;
use App\Services\Token\TokenService;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    public function issue(TokenService $tokenService, ShibbolethService $shibbolethService)
    {
       $tokenConfig = new TokenConfig($shibbolethService->getProperties());
       return $tokenService->issue($tokenConfig)->toString();
    }

    public function validateToken(TokenService $tokenService, Request $request)
    {
       return $tokenService->isValid($request->token);
    }
}
