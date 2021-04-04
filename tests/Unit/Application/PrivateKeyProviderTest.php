<?php
declare(strict_types = 1);

namespace Tests\Unit\Application;

use App\Application\PrivateKeyProvider;
use App\Domain\CertificateEntry;
use App\Domain\CertificateEntryRepository;
use App\Domain\FullChain;
use App\Domain\PrivateKey;
use PHPUnit\Framework\TestCase;

final class PrivateKeyProviderTest extends TestCase
{

    private PrivateKeyProvider $unit;

    protected function setUp(): void
    {
        $this->unit = new PrivateKeyProvider(
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
    public function resolves_key(): void
    {
        $resolver = 'acme';
        $domain   = 'localhost';

        self::assertSame(
            PrivateKey::dummy("$resolver, $domain")->toString(),
            call_user_func($this->unit, $resolver, $domain)
        );
    }

}
