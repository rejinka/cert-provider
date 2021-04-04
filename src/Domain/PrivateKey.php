<?php
declare(strict_types = 1);

namespace App\Domain;

final class PrivateKey
{
    private const HEADER = '-----BEGIN RSA PRIVATE KEY-----';
    private const FOOTER = '-----END RSA PRIVATE KEY-----';

    public static function fromBase64EncodedPem(string $value): self
    {
        return new self(base64_decode($value));
    }

    public static function dummy(string $data): self
    {
        return new self(self::HEADER . "\n" . $data . "\n" . self::FOOTER . "\n");
    }

    public function toString(): string
    {
        return $this->value;
    }

    private function __construct(private string $value)
    {
        $valid =
            str_starts_with($this->value, self::HEADER . "\n")
            &&
            str_ends_with($this->value, "\n" . self::FOOTER . "\n")
        ;

        if (!$valid) {
            throw new \InvalidArgumentException('Invalid key.');
        }
    }

}
