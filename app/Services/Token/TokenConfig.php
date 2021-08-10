<?php

namespace App\Services\Token;

use App\Services\Shibboleth\ShibbolethProperties;

class TokenConfig {
    public string $firstname;
    public string $lastname;
    public ?int $eventoId;
    public ?Role $role;


    public function __construct(ShibbolethProperties $shibbolethProperties) {
        $this->firstname = $shibbolethProperties->givenName ?: '';
        $this->lastname = $shibbolethProperties->surname ?: '';
        $this->eventoId = (int)$shibbolethProperties->fhnwIDPerson;
        $this->setRole($shibbolethProperties);
    }

    public function setRole(ShibbolethProperties $shibbolethProperties) {
        $this->role = null;
        if (str_contains($shibbolethProperties->fhnwDetailedAffiliation, 'staff-hls-alle')) {
            $this->role = Role::mentor();
            return;
        }

        if (!$shibbolethProperties->fhnwDetailedAffiliation && $shibbolethProperties->fhnwIDPerson){
            $this->role = Role::student();
            return;
        }
    }

    public function isValid():bool{
        if (!$this->firstname) {
            return false;
        }

        if (!$this->lastname) {
            return false;
        }

        if (!$this->role) {
            return false;
        }

        if ($this->role->getName() === 'student' && !$this->eventoId){
            return false;
        }

        return true;


    }
}