<?php

namespace App\Services\Token;

class Role {
    private string $name;

    private function __construct(string $name) {
        $this->name = $name;
    }
    
    public static function student() {
        return new Role('student');
    }

    public static function mentor() {
        return new Role('mentor');
    }

    public function getName():string {
        return $this->name;
    }
}