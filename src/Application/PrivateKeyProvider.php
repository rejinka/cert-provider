<?php
declare(strict_types = 1);

namespace App\Application;

use App\Domain\CertificateEntryRepository;
use App\Domain\Exception\EntityNotFoundException;

final class PrivateKeyProvider
{

    public function __construct(private CertificateEntryRepository $repository) {}

    /**
     * @param string $resolver
     * @param string $domain
     * @throws EntityNotFoundException
     * @return string
     */
    public function __invoke(string $resolver, string $domain): string
    {
        return $this->repository->get($resolver, $domain)
            ->getPrivateKey()
            ->toString();
    }

}
