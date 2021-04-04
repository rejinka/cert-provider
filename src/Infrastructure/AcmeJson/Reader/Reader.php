<?php
declare(strict_types = 1);

namespace App\Infrastructure\AcmeJson\Reader;

use App\Infrastructure\AcmeJson\ResolverList;

interface Reader
{

    public function read(): ResolverList;

}
