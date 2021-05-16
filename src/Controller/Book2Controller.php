<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Manager\BookManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/books")
 */
class Book2Controller extends AbstractController
{

    protected BookManager $bookManager;

    public function __construct(
        BookManager $bookManager
    ) {
        $this->bookManager = $bookManager;
    }

    /**
     * @Route(path="", name="books")
     */
    public function index(Request $request): Response
    {
        $books = $this->bookManager->paginate([
            'q' => $request->get('q'),
            'p' => $request->query->getInt('p', 1),
        ]);

        return $this->render('default/book/index.html.twig',
            [
                'books'       => $books,
                'bookManager' => $this->bookManager,
            ]
        );
    }
}
