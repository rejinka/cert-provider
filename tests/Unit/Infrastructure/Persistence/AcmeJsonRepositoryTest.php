<?php
declare(strict_types = 1);

namespace Tests\Unit\Infrastructure\Persistence;

use App\Domain\CertificateEntry;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\FullChain;
use App\Domain\PrivateKey;
use App\Infrastructure\AcmeJson\Item;
use App\Infrastructure\AcmeJson\Reader\Reader;
use App\Infrastructure\AcmeJson\Resolver;
use App\Infrastructure\AcmeJson\ResolverList;
use App\Infrastructure\Persistence\AcmeJsonRepository;
use PHPUnit\Framework\TestCase;

final class AcmeJsonRepositoryTest extends TestCase
{

    private AcmeJsonRepository $unit;

    protected function setUp(): void
    {
        $this->unit = new AcmeJsonRepository(new class implements Reader {
            public function read(): ResolverList
            {
                return new ResolverList(
                    [
                        'acme' => new Resolver(
                            [
                                new Item('localhost', FullChain::dummy('a'), PrivateKey::dummy('b')),
                                new Item('localhost', FullChain::dummy('c'), PrivateKey::dummy('d')),
                            ],
                        ),
                    ],
                );
            }
        });
    }

    /**
     * @test
     */
    public function throws_if_domain_does_not_match(): void
    {
        $this->expectException(EntityNotFoundException::class);

        $this->unit->get('acme', 'localhost2');
    }

    /**
     * @test
     */
    public function throws_if_resolver_does_not_match(): void
    {
        $this->expectException(EntityNotFoundException::class);

        $this->unit->get('acme2', 'localhost');
    }

    /**
     * @test
     */
    public function matches_first(): void
    {
        self::assertEquals(
            new CertificateEntry(FullChain::dummy('a'), PrivateKey::dummy('b')),
            $this->unit->get('acme', 'localhost'),
        );
    }

}
