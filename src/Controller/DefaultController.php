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
            switch (true) {
                case $user->isAdmin():
                    return $this->redirectToRoute('crud.admin.list');

                case $user->isReader():
                    return $this->redirectToRoute('reading');

            }

            return $this->redirectToRoute('profile');
        }

        return $this->render('default/homepage.html.twig');
    }

    public function cms(Request $request, string $slug): Response
    {
        throw new NotFoundHttpException();
    }
}
