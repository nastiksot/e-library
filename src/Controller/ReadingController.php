<?php
declare(strict_types=1);

namespace App\Controller;

use App\Form\Type\BookType;
use App\Service\Manager\BookManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/reading")
 */
class ReadingController extends AbstractController
{

    protected BookManager $bookManager;

    public function __construct(BookManager $bookManager)
    {
        $this->bookManager = $bookManager;
    }

    /**
     * @Route(path="", name="reading")
     */
    public function index(Request $request): Response
    {
        $books = $this->bookManager->paginate([
            'q' => $request->get('q'),
            'p' => $request->query->getInt('p', 1),
        ]);

        return $this->render('default/reading/index.html.twig',
            [
                'books'       => $books,
                'bookManager' => $this->bookManager,
            ]
        );
    }
//
//    /**
//     * @Route(path="/add", name="book.add")
//     */
//    public function add(Request $request): Response
//    {
//        $form = $this->bookManager->form(BookType::class);
//        if ($request->isMethod(Request::METHOD_POST) &&
//            !($errors = $this->bookManager->handleForm($form, $request))
//        ) {
//            $data = $form->getData();
//            $this->bookManager->create($data);
//
//            return $this->redirectToRoute('book.list');
//        }
//
//        return $this->render(
//            'default/crud/book/add.html.twig',
//            [
//                'form' => $form->createView(),
//            ]
//        );
//    }
//
//    /**
//     * @Route(path="/{id}/edit", name="book.edit")
//     */
//    public function edit(Request $request, int $id): Response
//    {
//        $data            = $this->bookManager->get($id);
//        $data['authors'] = !empty($data['authors']) ? array_keys($data['authors']) : [];
//        $form            = $this->bookManager->form(BookType::class, $data ?? [], ['id' => $id]);
//        if ($request->isMethod(Request::METHOD_POST) &&
//            !($errors = $this->bookManager->handleForm($form, $request))
//        ) {
//            $data = $form->getData();
//            $this->bookManager->update($id, $data);
//
//            return $this->redirectToRoute('book.list');
//        }
//
//        return $this->render(
//            'default/crud/book/edit.html.twig',
//            [
//                'id'   => $id,
//                'form' => $form->createView(),
//            ]
//        );
//    }
//
//    /**
//     * @Route(path="/{id}/delete", name="book.delete")
//     */
//    public function delete(int $id): Response
//    {
//        $this->bookManager->delete($id);
//
//        return $this->redirectToRoute('book.list');
//    }

}
