<?php
declare(strict_types = 1);

namespace App\Infrastructure\Persistence;

use App\Domain\CertificateEntry;
use App\Domain\CertificateEntryRepository;
use App\Domain\Exception\EntityNotFoundException;
use App\Infrastructure\AcmeJson;

final class AcmeJsonRepository implements CertificateEntryRepository
{

    public function __construct(private AcmeJson\Reader\Reader $reader) { }

    public function get(string $resolver, string $domain): CertificateEntry
    {
        try {
            $item = $this->reader->read()
                ->getResolver($resolver)
                ->getByMainDomain($domain);

            return new CertificateEntry($item->getFullChain(), $item->getPrivateKey());
        } catch (AcmeJson\ResolverNotFoundException|AcmeJson\MainDomainNotFoundException $e) {
            throw new EntityNotFoundException($resolver, $domain, $e);
        }
    }

}
