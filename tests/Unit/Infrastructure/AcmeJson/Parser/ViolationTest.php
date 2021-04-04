<?php
declare(strict_types = 1);

namespace Tests\Unit\Infrastructure\AcmeJson\Parser;

use App\Infrastructure\AcmeJson\Parser\Violation;
use PHPUnit\Framework\TestCase;

final class ViolationTest extends TestCase
{

    /**
     * @test
     */
    public function properties_accessible(): void
    {
        $data = [
            'a',
            'b',
            'c',
            'd',
        ];

        $violation = new Violation(...$data);

        self::assertSame($data, [
            $violation->getProperty(),
            $violation->getPointer(),
            $violation->getMessage(),
            $violation->getConstraint(),
        ]);

    }

}
