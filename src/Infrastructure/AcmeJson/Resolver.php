<?php
declare(strict_types = 1);

namespace App\Infrastructure\AcmeJson;

final class Resolver
{

    /**
     * @param Item[] $items
     */
    public function __construct(private array $items) { }

    public function getByMainDomain(string $domain): Item
    {
        $items = array_filter($this->items, fn(Item $item) => $item->getMainDomain() === $domain);

        return array_shift($items) ?: throw new MainDomainNotFoundException(sprintf('Domain "%s" not found.', $domain));
    }

}
