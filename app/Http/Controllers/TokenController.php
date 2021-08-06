<?php

namespace App\Http\Controllers;

use DateTimeImmutable;
use Lcobucci\Clock\FrozenClock;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint\StrictValidAt;

class TokenController extends Controller
{
    public function create()
    {
        $private = config('jwt.private');
        $public = config('jwt.public');

        $privateKey = InMemory::plainText($private);

        $publicKey = InMemory::plainText($public);

        $config = Configuration::forAsymmetricSigner(
            // You may use RSA or ECDSA and all their variations (256, 384, and 512) and EdDSA over Curve25519
            Signer\Ecdsa\Sha512::create(),
            $privateKey,
            $publicKey
            // You may also override the JOSE encoder/decoder if needed by providing extra arguments here
        );
        $now = new DateTimeImmutable();
        $config->setValidationConstraints(new StrictValidAt(new FrozenClock($now)));

        $token = $config->builder()
        // Configures the issuer (iss claim)
            ->issuedBy('http://example.com')
        // Configures the audience (aud claim)
            ->permittedFor('http://example.org')
        // Configures the id (jti claim)
            ->identifiedBy('4f1g23a12aa')
        // Configures the time that the token was issue (iat claim)
            ->issuedAt($now)
        // Configures the time that the token can be used (nbf claim)
            ->canOnlyBeUsedAfter($now->modify('-1 minute'))
        // Configures the expiration time of the token (exp claim)
            ->expiresAt($now->modify('+1 hour'))
        // Configures a new claim, called "uid"
            ->withClaim('evento_id', 154228)
        // Configures a new header, called "foo"
            ->withHeader('foo', 'bar')
        // Builds a new token
            ->getToken($config->signer(), $config->signingKey())
        ;

        return $config->validator()->assert($token, ...$config->validationConstraints());
    }
}
