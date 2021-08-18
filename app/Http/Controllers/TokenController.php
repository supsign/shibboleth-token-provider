<?php

namespace App\Http\Controllers;

use App\Services\Shibboleth\ShibbolethService;
use App\Services\Token\TokenService;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    public function issue(TokenService $tokenService, ShibbolethService $shibbolethService)
    {
        $shibbolethProperties = $shibbolethService->getProperties();

        return $tokenService->issue($shibbolethProperties)->toString();
    }

    public function validateToken(TokenService $tokenService, Request $request)
    {
        return $tokenService->isValid($request->token);
    }
}
