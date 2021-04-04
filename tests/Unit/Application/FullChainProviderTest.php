<?php
declare(strict_types = 1);

namespace Tests\Unit\Application;

use App\Application\FullChainProvider;
use App\Domain\CertificateEntry;
use App\Domain\CertificateEntryRepository;
use App\Domain\FullChain;
use App\Domain\PrivateKey;
use PHPUnit\Framework\TestCase;

final class FullChainProviderTest extends TestCase
{

    private FullChainProvider $unit;

    protected function setUp(): void
    {
        $this->unit = new FullChainProvider(
            new class implements CertificateEntryRepository {
                public function get(string $resolver, string $domain): CertificateEntry
                {
                    if ('' === $resolver && '' === $domain) {
                        throw new \RuntimeException();
                    }

                    return new CertificateEntry(
                        FullChain::dummy("$resolver, $domain"),
                        PrivateKey::dummy("$resolver, $domain"),
                    );
                }
            }
        );
    }

    /**
     * @test
     */
    public function does_not_catch(): void
    {
        $this->expectException(\RuntimeException::class);

        call_user_func($this->unit, '', '');
    }

    /**
     * @test
     */
    public function resolves_certificate(): void
    {
        $resolver = 'acme';
        $domain   = 'localhost';

        self::assertSame(
            FullChain::dummy("$resolver, $domain")->toString(),
            call_user_func($this->unit, $resolver, $domain)
        );
    }

}
