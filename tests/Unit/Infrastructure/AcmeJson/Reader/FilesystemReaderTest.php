<?php
declare(strict_types = 1);

namespace Tests\Unit\Infrastructure\AcmeJson\Reader;

use App\Infrastructure\AcmeJson\Parser\Parser;
use App\Infrastructure\AcmeJson\Reader\FilesystemReader;
use App\Infrastructure\AcmeJson\Resolver;
use App\Infrastructure\AcmeJson\ResolverList;
use PHPUnit\Framework\TestCase;

final class FilesystemReaderTest extends TestCase
{

    private string $path;

    private FilesystemReader $unit;

    protected function setUp(): void
    {
        $this->path = tempnam(sys_get_temp_dir(), '');
        $this->unit = new FilesystemReader(
            new class implements Parser {
                public function parse(string $content): ResolverList
                {
                    return new ResolverList(
                        [
                            $content => new Resolver([])
                        ]
                    );
                }
            },
            $this->path
        );
    }

    /**
     * @test
     */
    public function reads(): void
    {
        $data = 'a';
        file_put_contents($this->path, $data);

        self::assertInstanceOf(Resolver::class, $this->unit->read()->getResolver($data));
    }

    /**
     * @test
     */
    public function exception_on_io_error(): void
    {
        unlink($this->path);

        $this->expectException(\RuntimeException::class);

        $this->unit->read();
    }

    protected function tearDown(): void
    {
        @unlink($this->path);
    }

}
