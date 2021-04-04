<?php
declare(strict_types = 1);

namespace App\Infrastructure\AcmeJson\Parser;

use App\Domain\FullChain;
use App\Domain\PrivateKey;
use App\Infrastructure\AcmeJson\Item;
use App\Infrastructure\AcmeJson\Resolver;
use App\Infrastructure\AcmeJson\ResolverList;
use JsonSchema\Validator;
use stdClass;

final class JsonParser implements Parser
{

    private const SCHEMA =<<<'JSON'
{
    "type": "object",
    "patternProperties": {
        "^.*$": {
            "type": "object",
            "required": [
                "Certificates"
            ],
            "properties": {
                "Certificates": {
                    "type": "array",
                    "items": {
                        "type": "object",
                        "required": [
                            "domain",
                            "certificate",
                            "key"
                        ],
                        "properties": {
                            "domain": {
                                "type": "object",
                                "required": [
                                    "main"
                                ],
                                "properties": {
                                    "main": {
                                        "type": "string"
                                    }
                                }
                            },
                            "certificate": {
                                "type": "string"
                            },
                            "key": {
                                "type": "string"
                            }
                        }
                    }
                }
            }
        }
    }
}
JSON;

    private stdClass $schema;

    public function __construct()
    {
        $schema = json_decode(self::SCHEMA);
        assert($schema instanceof stdClass, 'Schema is invalid.');

        $this->schema = $schema;
    }

    public function parse(string $content): ResolverList
    {
        $raw = $this->validated($content);

        return new ResolverList(
            array_combine(
                array_keys($raw),
                array_map(
                    fn (array $row) => new Resolver(
                        array_map(
                            fn (array $row) => new Item(
                                $row['domain']['main'],
                                FullChain::fromBase64EncodedPem($row['certificate']),
                                PrivateKey::fromBase64EncodedPem($row['key'])
                            ),
                            $row['Certificates']
                        )
                    ),
                    $raw,
                ),
            ),
        );
    }

    /**
     * @param string $content
     * @throws GuardViolatedException
     * @return array<
     *     string,
     *     array{
     *          Certificates: array<
     *              array{
     *                  domain: array{
     *                      main: string
     *                  },
     *                  certificate: string,
     *                  key: string
     *              }
     *          >
     *      }
     * >
     *
     * @psalm-suppress MixedInferredReturnType
     */
    private function validated(string $content): array
    {
        $this->guard($content);

        /** @psalm-suppress MixedReturnStatement */
        return json_decode($content, true);
    }

    /**
     * @param string $content
     * @throws GuardViolatedException
     */
    private function guard(string $content): void
    {
        $decoded = json_decode($content);
        if (!$decoded instanceof stdClass) {
            throw GuardViolatedException::raiseStructuralInvalid();
        }

        $validator = new Validator();
        $validator->validate($decoded, $this->schema);

        if (!$validator->isValid()) {
            /** @var array<array{property: string, pointer: string, message: string, constraint: string}> $errors */
            $errors = $validator->getErrors();

            throw GuardViolatedException::raiseSchemaViolation(
                array_map(
                    fn(array $data) => new Violation(
                        $data['property'],
                        $data['pointer'],
                        $data['message'],
                        $data['constraint']
                    ),
                    $errors
                )
            );
        }
    }

}
