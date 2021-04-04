<?php
declare(strict_types = 1);

namespace App\Infrastructure\AcmeJson\Reader;

use App\Infrastructure\AcmeJson\Parser\Parser;
use App\Infrastructure\AcmeJson\ResolverList;
use RuntimeException;

final class FilesystemReader implements Reader
{

    public function __construct(private Parser $parser, private string $path) { }

    public function read(): ResolverList
    {
        $content = @file_get_contents($this->path);
        if (false === $content) {
            throw new RuntimeException(sprintf('"%s" not readable.', $this->path));
        }

        return $this->parser->parse($content);
    }

}
