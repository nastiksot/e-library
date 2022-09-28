<?php

declare(strict_types=1);

namespace App\Service\ImageManager;

use Imagick;
use ImagickException;
use Intervention\Image\Constraint;
use InvalidArgumentException;
use SplFileInfo;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use function file_exists;
use function implode;
use function in_array;
use function md5;
use function pathinfo;
use function strtolower;
use function substr;
use const PATHINFO_EXTENSION;

class ImageManager
{
    private const ALLOWED_BASE_DIR_DATA = [
        'images' => [
            'default',
            'page',
            'product',
            'product_set',
            'youtube',
            'partner',
            'dealer',
        ],
        'files' => [
            'slider',
            'descriptive_panel',
        ],
    ];

    private const ALLOWED_CROP = [
        'fit',
        'resize',
    ];

    public function __construct(
        private string $cacheDir,
        private string $dataDir,
        private \Intervention\Image\ImageManager $manager
    ) {
    }

    /**
     * @throws InvalidArgumentException
     * @throws IOException
     */
    public function getModifiedImage(string $crop, string $baseDir, string $type, int $width, int $height, string $name): SplFileInfo
    {
        if (!isset(self::ALLOWED_BASE_DIR_DATA[$baseDir])) {
            throw new InvalidArgumentException("Base Dir '{$baseDir}' is not allowed");
        }

        if (!in_array($type, self::ALLOWED_BASE_DIR_DATA[$baseDir], true)) {
            throw new InvalidArgumentException("Type '{$type}' in base dir '$baseDir' is not allowed");
        }

        if (!in_array($crop, self::ALLOWED_CROP, true)) {
            throw new InvalidArgumentException("Crop '{$crop}' is not allowed");
        }

        // get image paths
        [$storagePath, $cachePath] = $this->resolveImagePaths($baseDir, $type, $name, $crop, $width, $height);

        // mkdir to the $processedImage
        $originalImage  = new SplFileInfo($storagePath);
        $processedImage = new SplFileInfo($cachePath);

        if (!$processedImage->isFile()) {
            $fs = new Filesystem();
            $fs->mkdir($processedImage->getPath());
        }

        // process image
        if (!file_exists($processedImage->getPathname()) || $processedImage->getCTime() < $originalImage->getCTime()) {
            $this->process($crop, $width, $height, $originalImage, $processedImage);
        }

        return $processedImage;
    }

    protected function resolveImagePaths(string $baseDir, string $type, string $name, string $crop, int $width, int $height): array
    {
        if (file_exists($this->dataDir . '/' . $baseDir . '/' . $type . '/' . $name)) {
            $storagePath = $this->dataDir . '/' . $baseDir . '/' . $type . '/' . $name;
        } elseif (file_exists($this->dataDir . '/' . $baseDir . '/' . $type . '/default.jpg')) {
            $storagePath = $this->dataDir . '/' . $baseDir . '/' . $type . '/default.jpg';
        } else {
            $storagePath = $this->dataDir . '/' . $baseDir . '/default.jpg';
        }

        $cacheKey  = md5(implode('_xxx_', [$crop, $type, $width, $height, $name]));
        $cacheDir  = $this->cacheDir . '/' . $baseDir . '/' . substr($cacheKey, 1, 2) . '/' . substr($cacheKey, 3, 4);
        $extension = pathinfo($name, PATHINFO_EXTENSION);
        $cachePath = $cacheDir . '/' . $cacheKey . ($extension ? '.' . $extension : '');

        return [$storagePath, $cachePath];
    }

    protected function process(
        string $crop,
        int $width,
        int $height,
        SplFileInfo $originalImage,
        SplFileInfo $processedImage
    ): void {
        $image = $this->manager->make($originalImage);

        $constraint = static function (Constraint $constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        };

        switch ($crop) {
            case 'fit':
                $image->fit($width, $height, $constraint, 'center');

                break;
            case 'resize':
                $image->resize($width, $height, $constraint);

                break;
        }

        // save image
        $image->save($processedImage->getPathname(), 100);

        // optimize
        $driverName = $image->getDriver() ? strtolower($image->getDriver()->getDriverName()) : null;

        if ('imagick' === $driverName) {
            $this->handleImageMagick($processedImage->getPathname());
        }
    }

    protected function handleImageMagick(string $imagePath): bool
    {
        try {
            $im = new Imagick($imagePath);
            $im->setSamplingFactors(['2x2', '1x1', '1x1']);
            $im->stripImage();
            $im->writeImage($imagePath);
        } catch (ImagickException $e) {
            return false;
        }

        // return success
        return true;
    }
}
