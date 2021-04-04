<?php
declare(strict_types = 1);

namespace App\Infrastructure\AcmeJson;

final class ResolverList
{

    /**
     * @param array<string, Resolver> $resolver
     */
    public function __construct(private array $resolver) { }

    public function getResolver(string $name): Resolver
    {
        return $this->resolver[$name] ?? throw new ResolverNotFoundException(sprintf('Resolver "%s" not found.', $name));
    }

}
