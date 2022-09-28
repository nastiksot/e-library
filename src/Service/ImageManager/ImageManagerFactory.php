<?php

declare(strict_types=1);

namespace App\Service\ImageManager;

use Intervention\Image\ImageManager;
use function extension_loaded;

class ImageManagerFactory
{
    public static function create(): ImageManager
    {
        return new ImageManager(['driver' => extension_loaded('imagick') ? 'imagick' : 'gd']);
    }
}
