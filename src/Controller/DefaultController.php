<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route(path="/", name="homepage")
     */
    public function index(): Response
    {
        $user = $this->getUser();

        if ($user) {

            if ($user->isAdmin()) {
                return $this->redirectToRoute('admin.list');
            }

            if ($user->isAuthor()) {
                return $this->redirectToRoute('profile.author');
            }

            return $this->redirectToRoute('profile.reader');
        }


        return $this->render('default/homepage.html.twig');
    }

    public function cms(Request $request, string $slug): Response
    {
        throw new NotFoundHttpException();
    }
}
