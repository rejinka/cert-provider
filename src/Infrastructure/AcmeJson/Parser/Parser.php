<?php
declare(strict_types = 1);

namespace App\Infrastructure\AcmeJson\Parser;

use App\Infrastructure\AcmeJson\ResolverList;

interface Parser
{

    public function parse(string $content): ResolverList;

}
