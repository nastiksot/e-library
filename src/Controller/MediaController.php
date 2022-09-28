<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ImageManager\ImageManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use SplFileInfo;
use function explode;
use function file_exists;
use function preg_match;

class MediaController extends AbstractController
{
    #[Route(
        path: '/images/{type}/{crop}/{size}/{name}',
        name: 'web.image',
        requirements: ['crop' => 'fit|resize'],
        options: ['expose' => true],
        defaults: ['baseDir' => 'images'],
    )]
    #[Route(
        path: '/files/{type}/{crop}/{size}/{name}',
        name: 'web.file',
        requirements: ['crop' => 'fit|resize'],
        options: ['expose' => true],
        defaults: ['baseDir' => 'files'],
    )]
    public function image(
        Request $request,
        ImageManager $imageManager,
        string $type,
        string $crop,
        string $size,
        string $name
    ): Response {
        if (!preg_match("/[\d]+x[\d]+/", $size)) {
            throw new NotFoundHttpException();
        }
        $baseDir = $request->attributes->get('baseDir');

        [$width, $height] = explode('x', $size);

        $image = $imageManager->getModifiedImage($crop, $baseDir, $type, (int) $width, (int) $height, $name);

        return $this->handleFileResponse($request, $image);
    }

    #[Route(
        path: '/media/{type}/{name}',
        name: 'web.media.original',
        requirements: ['type' => 'slider|descriptive_panel'],
        options: ['expose' => true],
        defaults: ['baseDir' => 'files'],
    )]
    public function originalMedia(
        Request $request,
        string $type,
        string $name
    ): Response {
        $dataDir = $this->getParameter('dataDir');
        $baseDir = $request->attributes->get('baseDir');

        if (file_exists($dataDir . '/' . $baseDir . '/' . $type . '/' . $name)) {
            $storagePath = $dataDir . '/' . $baseDir . '/' . $type . '/' . $name;
        } elseif (file_exists($dataDir . '/' . $baseDir . '/' . $type . '/default.jpg')) {
            $storagePath = $dataDir . '/' . $baseDir . '/' . $type . '/default.jpg';
        } else {
            $storagePath = $dataDir . '/' . $baseDir . '/default.jpg';
        }

        $mediaFile = new SplFileInfo($storagePath);

        return $this->handleFileResponse($request, $mediaFile);
    }
}
