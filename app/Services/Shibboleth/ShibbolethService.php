<?php

namespace App\Services\Shibboleth;

use Illuminate\Support\Facades\Request;

class ShibbolethService {

    private ShibbolethProperties $properties;

    public function __construct() {
        $this->laodProperties();
    }

    private function laodProperties():ShibbolethProperties{
        $data = new ShibbolethProperties();

        $data->shibSessionId = Request::server('Shib-Session-ID');
        $data->shibIdentityProvider = Request::server('Shib-Identity-Provider');
        $data->entitlement = Request::server('entitlement');
        $data->fhnwDetailedAffiliation = Request::server('fhnwDetailedAffiliation');
        $data->fhnwIDPerson = Request::server('fhnwIDPerson');
        $data->givenName = Request::server('givenName');
        $data->mail = Request::server('mail');
        $data->surname = Request::server('surname');

        $this->properties = $data;
        return $data;
    }

    public function getProperties() {
        return $this->properties;
    }
}