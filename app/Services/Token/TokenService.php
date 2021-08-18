<?php

namespace App\Services\Token;

use App\Services\Shibboleth\ShibbolethProperties;
use DateTimeImmutable;
use Lcobucci\Clock\FrozenClock;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\StrictValidAt;
use Lcobucci\JWT\Validation\Validator;

class TokenService
{
    public function issue(ShibbolethProperties $shibbolethProperties): Plain
    {
        $private = config('jwt.private');
        $privateKey = InMemory::plainText($private);
        $builder = new Builder(new JoseEncoder(), ChainedFormatter::default());
        $now = new DateTimeImmutable();

        return $builder->issuedBy('https://mst.fhnw.ch')
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now)
            ->expiresAt($now->modify('+1 hour'))
            ->withClaim('fhnwIDPerson', $shibbolethProperties->fhnwIDPerson)
            ->withClaim('mail', $shibbolethProperties->mail)
            ->withClaim('firstname', $shibbolethProperties->givenName)
            ->withClaim('lastname', $shibbolethProperties->surname)
            ->withClaim('fhnwDetailedAffiliation', $shibbolethProperties->fhnwDetailedAffiliation)
            ->getToken(Signer\Ecdsa\Sha512::create(), $privateKey)
        ;
    }

    public function isValid(string $jwt = null): bool
    {
        if (!$jwt) {
            return false;
        }

        $token = $this->parse($jwt);
        $now = new FrozenClock(new DateTimeImmutable());

        $validator = new Validator();

        $public = config('jwt.public');
        $publicKey = InMemory::plainText($public);
        $signer = Signer\Ecdsa\Sha512::create();

        return $validator->validate($token, new SignedWith($signer, $publicKey), new StrictValidAt($now));
    }

    private function parse(string $jwt): Plain
    {
        $parser = new Parser(new JoseEncoder());

        return $parser->parse($jwt);
    }
}
