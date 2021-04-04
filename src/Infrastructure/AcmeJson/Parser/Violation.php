<?php
declare(strict_types = 1);

namespace App\Infrastructure\AcmeJson\Parser;

final class Violation
{

    public function __construct(
        private string $property,
        private string $pointer,
        private string $message,
        private string $constraint
    ) {}

    public function getProperty(): string
    {
        return $this->property;
    }

    public function getPointer(): string
    {
        return $this->pointer;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getConstraint(): string
    {
        return $this->constraint;
    }

}
