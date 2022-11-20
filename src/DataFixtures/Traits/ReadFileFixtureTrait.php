<?php

declare(strict_types=1);

namespace App\DataFixtures\Traits;

use Generator;

use function fgets;
use function fopen;
use function trim;

trait ReadFileFixtureTrait
{
    protected function readFile(string $filePath): ?Generator
    {
        $file = fopen($filePath, 'rb');
        if (!$file) {
            return null;
        }
        while (($line = fgets($file)) !== false) {
            $line = trim($line);
            if ($line) {
                yield trim($line);
            }
        }

        fclose($file);
    }
}
