<?php

namespace RestBundle\Component;

final class TokenGenerator
{
    final public function generate()
    {
        return base64_encode(openssl_random_pseudo_bytes(mt_rand(64, 128)));
    }
}