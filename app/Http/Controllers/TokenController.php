<?php

namespace App\Http\Controllers;

use App\Services\Shibboleth\ShibbolethService;
use App\Services\Token\TokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class TokenController extends Controller
{
    public function issue(TokenService $tokenService, ShibbolethService $shibbolethService)
    {
        $shibbolethProperties = $shibbolethService->getProperties();

        $token = $tokenService->issue($shibbolethProperties)->toString();

        return view('forward', ['token' => $token]);
    }

    public function issueAsStudent(Request $request, TokenService $tokenService, ShibbolethService $shibbolethService)
    {
        if (!App::environment('local')){
            abort(403);
        }

        $shibbolethProperties = $shibbolethService->getProperties();
        $shibbolethProperties->fhnwIDPerson = $request->fhnwIDPerson ?? 5;
        $shibbolethProperties->givenName = 'Max';
        $shibbolethProperties->surname = 'Müller';
        $shibbolethProperties->mail = 'admin@supsign.ch';

        $token = $tokenService->issue($shibbolethProperties)->toString();

        return view('forward', ['token' => $token]);
    }

    public function validateToken(TokenService $tokenService, Request $request)
    {
        return $tokenService->isValid($request->token);
    }
}
