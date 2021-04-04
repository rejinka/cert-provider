<?php
declare(strict_types = 1);

namespace App\Domain\Exception;

use RuntimeException;
use Throwable;

final class EntityNotFoundException extends RuntimeException
{

    public function __construct(string $resolver, string $domain, Throwable $previous = null)
    {
        parent::__construct(sprintf('Could not find domain "%s" in resolver "%s".', $domain, $resolver), 0, $previous);
    }

}
