<?php

declare(strict_types=1);

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class LocaleFormatExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('localeFormat', [$this, 'localeFormat']),
        ];
    }

    public function localeFormat(string $locale): string
    {
        return str_replace('-', '_', $locale);
    }
}
