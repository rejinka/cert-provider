<?php
declare(strict_types = 1);

namespace App\Infrastructure\AcmeJson;

use App\Domain\FullChain;
use App\Domain\PrivateKey;

final class Item
{

    public function __construct(private string $mainDomain, private FullChain $fullChain, private PrivateKey $privateKey) { }

    public function getMainDomain(): string
    {
        return $this->mainDomain;
    }

    public function getFullChain(): FullChain
    {
        return $this->fullChain;
    }

    public function getPrivateKey(): PrivateKey
    {
        return $this->privateKey;
    }

}
