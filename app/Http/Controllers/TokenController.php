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

    public function issueAsAdmin(TokenService $tokenService, ShibbolethService $shibbolethService)
    {
        if (!App::environment('local')){
            abort(403);
        }

        $shibbolethProperties = $shibbolethService->getProperties();
        $shibbolethProperties->fhnwIDPerson = 1;
        $shibbolethProperties->givenName = 'Admin';
        $shibbolethProperties->surname = 'Admin';
        $shibbolethProperties->mail = 'hls@supsign.ch';
        $shibbolethProperties->entitlement = 'http://fhnw.ch/aai/res/hls/stab/mst_admin';

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
        $shibbolethProperties->entitlement = 'http://fhnw.ch/aai/res/hls/stab/mst_edu_student';

        $token = $tokenService->issue($shibbolethProperties)->toString();

        return view('forward', ['token' => $token]);
    }

    public function issueAsMentor(Request $request, TokenService $tokenService, ShibbolethService $shibbolethService)
    {
        if (!App::environment('local')){
            abort(403);
        }

        $shibbolethProperties = $shibbolethService->getProperties();
        $shibbolethProperties->fhnwIDPerson = $request->fhnwIDPerson ?? 2;
        $shibbolethProperties->givenName = 'Till';
        $shibbolethProperties->surname = 'Müller';
        $shibbolethProperties->mail = 'tillMüller@supsign.ch';
        $shibbolethProperties->entitlement = 'http://fhnw.ch/aai/res/hls/stab/mst_mentor';

        $token = $tokenService->issue($shibbolethProperties)->toString();

        return view('forward', ['token' => $token]);
    }

    public function validateToken(TokenService $tokenService, Request $request)
    {
        return $tokenService->isValid($request->token);
    }
}
