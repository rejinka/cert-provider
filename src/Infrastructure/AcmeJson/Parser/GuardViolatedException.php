<?php
declare(strict_types = 1);

namespace App\Infrastructure\AcmeJson\Parser;

final class GuardViolatedException extends \Exception
{

    /**
     * @var Violation[]
     */
    private array $violations;

    public static function raiseStructuralInvalid(): self
    {
        return new self('The data was not parsable.');
    }

    /**
     * @param Violation[] $violations
     */
    public static function raiseSchemaViolation(array $violations): self
    {
        return new self('The data violated the schema.', $violations);
    }

    /**
     * @param string      $message
     * @param Violation[] $violations
     */
    private function __construct($message = "", array $violations = [])
    {
        parent::__construct($message);

        $this->violations = $violations;
    }

}
