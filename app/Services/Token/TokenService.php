<?php

namespace App\Services\Token;

use DateTimeImmutable;
use Lcobucci\Clock\FrozenClock;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\StrictValidAt;
use Lcobucci\JWT\Validation\Validator;
use phpDocumentor\Reflection\Types\Boolean;
use PhpParser\Node\Expr\Cast\Bool_;

class TokenService {
    
    public function issue(TokenConfig $tokenConfig):Plain {
        if (!$tokenConfig->isValid()){
            abort(400, 'TokenConfig not valid');
        }

        
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

        return $config->builder()
        // Configures the issuer (iss claim)
             ->issuedBy('https://mst.fhnw.ch')
            ->issuedAt($now)
            ->expiresAt($now->modify('+1 hour'))
            ->withClaim('evento_id', $tokenConfig->eventoId)
            ->withClaim('firstname', $tokenConfig->firstname)
            ->withClaim('lastname', $tokenConfig->lastname)
            ->withClaim('role', $tokenConfig->role->getName())
            ->getToken($config->signer(), $config->signingKey());
        ;
    }

    public function isValid(String $jwt = null):bool {

        if (!$jwt){
            return false;
        }

        $parser = new Parser(new JoseEncoder());

        $token = $parser->parse($jwt);

        $validator = new Validator();
        $public = config('jwt.public');
        $publicKey = InMemory::plainText($public);
        $signer = Signer\Ecdsa\Sha512::create();

        return $validator->validate($token, new SignedWith($signer,$publicKey));
    }
}