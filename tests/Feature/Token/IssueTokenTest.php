<?php

namespace Tests\Feature\Token;

use App\Services\Shibboleth\ShibbolethProperties;
use App\Services\Token\TokenService;
use Lcobucci\JWT\Token\Plain;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class IssueTokenTest extends TestCase
{
    public function testIssueToken()
    {
        $shibbolethProperties = new ShibbolethProperties();
        $shibbolethProperties->surname = 'blub';
        $shibbolethProperties->givenName = 'blub2';
        $shibbolethProperties->fhnwIDPerson = 1234;
        $tokenService = new TokenService();

        $token = $tokenService->issue($shibbolethProperties);

        $this->assertInstanceOf(Plain::class, $token);
        $this->assertTrue($tokenService->isValid($token->toString()));
    }
}
