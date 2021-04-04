<?php
declare(strict_types = 1);

namespace Tests\Unit\Infrastructure\AcmeJson\Parser;

use App\Domain\FullChain;
use App\Domain\PrivateKey;
use App\Infrastructure\AcmeJson\Item;
use App\Infrastructure\AcmeJson\Parser\GuardViolatedException;
use App\Infrastructure\AcmeJson\Parser\JsonParser;
use App\Infrastructure\AcmeJson\Parser\Violation;
use App\Infrastructure\AcmeJson\Resolver;
use App\Infrastructure\AcmeJson\ResolverList;
use PHPUnit\Framework\TestCase;

final class JsonParserTest extends TestCase
{

    private JsonParser $unit;

    protected function setUp(): void
    {
        $this->unit = new JsonParser();
    }

    /**
     * @test
     */
    public function correctly_parses(): void
    {
        $encodeKey = function (string $value): string {
            return base64_encode(PrivateKey::dummy($value)->toString());
        };

        $encodeCert = function(string $value): string {
            return base64_encode(FullChain::dummy($value)->toString());
        };

        $json =<<<JSON
{
    "acme": {
        "Certificates": [
            {
                "domain": {
                    "main": "a_domain"
                },
                "certificate": "{$encodeCert('a_cert')}",
                "key": "{$encodeKey('a_key')}"
            },
            {
                "domain": {
                    "main": "b_domain"
                },
                "certificate": "{$encodeCert('b_cert')}",
                "key": "{$encodeKey('b_key')}"
            }
        ]
    },
    "letsencrypt": {
        "Certificates": [
            {
                "domain": {
                    "main": "c_domain"
                },
                "certificate": "{$encodeCert('c_cert')}",
                "key": "{$encodeKey('c_key')}"
            },
            {
                "domain": {
                    "main": "d_domain"
                },
                "certificate": "{$encodeCert('d_cert')}",
                "key": "{$encodeKey('d_key')}"
            }
        ]
    }
}
JSON;

        $this->unit->parse($json);

        $expected = new ResolverList(
            [
                'acme' => new Resolver(
                    [
                        new Item('a_domain', FullChain::dummy('a_cert'), PrivateKey::dummy('a_key')),
                        new Item('b_domain', FullChain::dummy('b_cert'), PrivateKey::dummy('b_key')),
                    ]
                ),
                'letsencrypt' => new Resolver(
                    [
                        new Item('c_domain', FullChain::dummy('c_cert'), PrivateKey::dummy('c_key')),
                        new Item('d_domain', FullChain::dummy('d_cert'), PrivateKey::dummy('d_key')),
                    ]
                ),
            ]
        );

        self::assertEquals($expected, $this->unit->parse($json));
    }

    /**
     * @test
     * @dataProvider jsonProvider
     *
     * @param string $path
     */
    public function does_not_throw_on_different_traefik_versions(string $path): void
    {
        $this->unit->parse($path);

        self::assertTrue(true);
    }

    /**
     * @test
     */
    public function is_guarded_against_structural_invalid_json(): void
    {
        $input =<<<EOF
{
    "acme": {
    }
EOF;

        $this->expectExceptionObject(GuardViolatedException::raiseStructuralInvalid());

        $this->unit->parse($input);
    }

    /**
     * @test
     */
    public function is_guarded_against_semantically_invalid_json(): void
    {
        $input =<<<JSON
{
    "acme": {
        "Certificates": [
            {
                "domain": {
                    "main": "localhost"
                },
                "key": "123",
                "certificate": {
                    "value": "xy"
                }
            },
            {
                "domain": {
                    "main": "localhost"
                },
                "certificate": "yessir"
            }
        ]
    }
}
JSON;

        $expected = GuardViolatedException::raiseSchemaViolation(
            [
                new Violation(
                    'acme.Certificates[0].certificate',
                    '/acme/Certificates/0/certificate',
                    'Object value found, but a string is required',
                    'type',
                ),
                new Violation(

                    'acme.Certificates[1].key',
                    '/acme/Certificates/1/key',
                    'The property key is required',
                    'required',
                ),
            ]
        );

        try {
            $this->unit->parse($input);

            self::fail();
        } catch (GuardViolatedException $catched) {
            self::assertEquals($expected, $catched);
        }
    }

    public function jsonProvider(): array
    {
        $files = glob(__DIR__ . '/fixtures/acme.json/*');

        return array_combine(
            array_map(
                fn(string $path) => pathinfo($path, PATHINFO_FILENAME),
                $files
            ),
            array_map(
                fn(string $path) => [file_get_contents($path)],
                $files
            )
        );
    }

}
