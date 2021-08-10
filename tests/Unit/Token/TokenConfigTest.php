<?php

namespace Tests\Unit\Token;

use App\Services\Shibboleth\ShibbolethProperties;
use App\Services\Token\TokenConfig;
use PHPUnit\Framework\TestCase;

class TokenConfigTest extends TestCase
{

    public function testCreateInvalidTokenConfig()
    {
        $shibbolethProperties = new ShibbolethProperties();
        $shibbolethProperties->surname = 'blub';
        $shibbolethProperties->givenName = 'blub2';
        $tokenConfig = new TokenConfig($shibbolethProperties);
        $this->assertInstanceOf(TokenConfig::class, $tokenConfig);
        $this->assertTrue(!$tokenConfig->isValid());
    }

    public function testCreateStudentTokenConfig()
    {
        $shibbolethProperties = new ShibbolethProperties();
        $shibbolethProperties->surname = 'blub';
        $shibbolethProperties->givenName = 'blub2';
        $shibbolethProperties->fhnwIDPerson = 1234;
        $tokenConfig = new TokenConfig($shibbolethProperties);
        $this->assertInstanceOf(TokenConfig::class, $tokenConfig);
        $this->assertTrue($tokenConfig->isValid());
    }

    public function testCreateMentorTokenConfig()
    {
        $shibbolethProperties = new ShibbolethProperties();
        $shibbolethProperties->surname = 'blub';
        $shibbolethProperties->givenName = 'blub2';
        $shibbolethProperties->fhnwDetailedAffiliation = 'staff-hls-alle';
        $tokenConfig = new TokenConfig($shibbolethProperties);
        $this->assertInstanceOf(TokenConfig::class, $tokenConfig);
        $this->assertTrue($tokenConfig->isValid());
    }


}
