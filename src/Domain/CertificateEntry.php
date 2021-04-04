<?php
declare(strict_types = 1);

namespace App\Domain;

final class CertificateEntry
{

    public function __construct(private FullChain $fullChain, private PrivateKey $privateKey) { }

    public function getFullChain(): FullChain
    {
        return $this->fullChain;
    }

    public function getPrivateKey(): PrivateKey
    {
        return $this->privateKey;
    }

}
