<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Contracts\UserInterface;
use App\Form\Type\BookType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/book")
 */
class BookController extends AbstractController
{
    /**
     * @Route(path="/list", name="book.list")
     */
    public function index(): Response
    {
        $books = $this->bookRepository->getAll();

        return $this->render('default/crud/book/index.html.twig',
            [
                'books' => $books,
            ]
        );
    }

    /**
     * @Route(path="/add", name="book.add")
     */
    public function add(Request $request): Response
    {
        $form = $this->createBookForm(BookType::class);
        if ($request->isMethod(Request::METHOD_POST) &&
            !($errors = $this->formHandler->handleForm($form, $request))
        ) {
            $this->bookRepository->createBook($form->getData());
            return $this->redirectToRoute('book.list');
        }

        return $this->render('default/crud/book/add.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route(path="/{id}/edit", name="book.edit")
     */
    public function edit(Request $request, int $id): Response
    {
        $book = $this->bookRepository->getOneById($id);
        if (!$book) {
            return $this->redirectToRoute('book.list');
        }

        $form = $this->createBookForm(BookType::class, $book, ['id' => $id]);
        if ($request->isMethod(Request::METHOD_POST) &&
            !($errors = $this->formHandler->handleForm($form, $request))
        ) {
            $this->bookRepository->updateBook($id, $form->getData());

            return $this->redirectToRoute('book.list');
        }

        return $this->render('default/crud/book/edit.html.twig',
            [
                'id'   => $id,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route(path="/{id}/delete", name="book.delete")
     */
    public function delete(int $id): Response
    {
        $this->bookRepository->deleteOneById($id);

        return $this->redirectToRoute('book.list');
    }

}
