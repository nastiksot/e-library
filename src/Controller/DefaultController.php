<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route(
        path: '/',
        name: 'default.homepage'
    )]
    public function index(): Response
    {
        return $this->redirectToRoute('admin_book_list');
    }
}
