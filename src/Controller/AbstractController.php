<?php

declare(strict_types=1);

namespace App\Controller;

use App\Contracts\Entity\UserInterface;
use Carbon\Carbon;
use SplFileInfo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseAbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * @method UserInterface|null getUser()
 */
abstract class AbstractController extends BaseAbstractController
{
    protected function handleFileResponse(Request $request, ?SplFileInfo $file, ?string $disposition = null): Response
    {
        if (null === $file || !$file->isFile()) {
            throw $this->createNotFoundException('File not found.');
        }

        $lastModifiedDate = new Carbon('@' . $file->getCTime());

        if ($request->headers->has('If-Modified-Since') &&
            new Carbon($request->headers->get('If-Modified-Since')) >= $lastModifiedDate
        ) {
            return new Response(null, Response::HTTP_NOT_MODIFIED);
        }

        return (new BinaryFileResponse($file))
            ->setLastModified($lastModifiedDate)
            ->setAutoEtag()
            ->setContentDisposition($disposition ?? ResponseHeaderBag::DISPOSITION_INLINE);
    }

    protected function handleFileResponseAttachment(Request $request, ?SplFileInfo $file = null): Response
    {
        return $this->handleFileResponse($request, $file, ResponseHeaderBag::DISPOSITION_ATTACHMENT);
    }
}
