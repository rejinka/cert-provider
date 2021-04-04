<?php
declare(strict_types = 1);

namespace App\Domain;

use App\Domain\Exception\EntityNotFoundException;

interface CertificateEntryRepository
{

    /**
     * @param string $resolver
     * @param string $domain
     * @throws EntityNotFoundException
     * @return CertificateEntry
     */
    public function get(string $resolver, string $domain): CertificateEntry;

}
