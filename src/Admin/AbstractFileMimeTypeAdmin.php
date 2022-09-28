<?php

declare(strict_types=1);

namespace App\Admin;

use App\Contracts\Dictionary\FileMimeType;
use App\Contracts\Entity\FileMimeTypeInterface;
use App\Contracts\Entity\SliderFileMimeTypeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractFileMimeTypeAdmin extends AbstractAdmin
{
    /** @var EntityManagerInterface|null */
    private $em;

    #[Required]
    public function setEntityManager(EntityManagerInterface $em): void
    {
        $this->em = $em;
    }

    protected function postPersist(object $object): void
    {
        $this->updateMimeType($object);
    }

    protected function postUpdate(object $object): void
    {
        $this->updateMimeType($object);
    }

    protected function updateMimeType(SliderFileMimeTypeInterface|FileMimeTypeInterface $entity): void
    {
        $isUpdated = false;

        if ($entity->getFileName() && $entity->getFile()) {
            // `file` field was set/changed
            $fileMimeType = $this->calculateFileMimeType($entity->getFile());

            if ($fileMimeType) {
                $entity->setFileMimeType($fileMimeType);
                $isUpdated = true;
            }
        } elseif (!$entity->getFileName() && !$entity->getFile()) {
            // `file` field was removed
            $entity->setFileMimeType(null);
            $isUpdated = true;
        }

        if ($entity instanceof SliderFileMimeTypeInterface) {
            if ($entity->getFileMobileName() && $entity->getFileMobile()) {
                // `file_mobile` field was set/changed
                $fileMobileMimeType = $this->calculateFileMimeType($entity->getFileMobile());

                if ($fileMobileMimeType) {
                    $entity->setFileMobileMimeType($fileMobileMimeType);
                    $isUpdated = true;
                }
            } elseif (!$entity->getFileMobileName() && !$entity->getFileMobile()) {
                // `file` field was removed
                $entity->setFileMobileMimeType(null);
                $isUpdated = true;
            }
        }

        if ($isUpdated) {
            $this->em->flush();
        }
    }

    private function calculateFileMimeType(File $file): ?string
    {
        $mimeType = $file->getMimeType();

        if ($mimeType) {
            $mimeTypeComponents = array_map('strtolower', explode('/', $mimeType));
            $imageType          = FileMimeType::IMAGE()->getValue();
            $videoType          = FileMimeType::VIDEO()->getValue();

            if ($mimeTypeComponents[0] === $imageType) {
                if ('gif' === $mimeTypeComponents[1] && $this->isAnimatedGifFile($file)) {
                    return FileMimeType::ANIMATED_GIF()->getValue();
                }

                return $imageType;
            }

            if ($mimeTypeComponents[0] === $videoType) {
                return $videoType;
            }
        }

        return null;
    }

    private function isAnimatedGifFile(File $gifFile): bool
    {
        if (!($fh = @fopen($gifFile->getPathname(), 'rb'))) {
            return false;
        }

        $count = 0;

        //an animated gif contains multiple "frames", with each frame having a
        //header made up of:
        // * a static 4-byte sequence (\x00\x21\xF9\x04)
        // * 4 variable bytes
        // * a static 2-byte sequence (\x00\x2C) (some variants may use \x00\x21 ?)

        // We read through the file til we reach the end of the file, or we've found
        // at least 2 frame headers
        while (!feof($fh) && $count < 2) {
            $chunk = fread($fh, 1024 * 100); //read 100kb at a time
            $count += preg_match_all('#\x00\x21\xF9\x04.{4}\x00(\x2C|\x21)#s', $chunk, $matches);
        }

        fclose($fh);

        return $count > 1;
    }
}
