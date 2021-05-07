<?php
declare(strict_types=1);

namespace App\Controller;

use App\Form\Type\User\ReaderUserType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/profile")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route(path="", name="profile")
     */
    public function index(Request $request): Response
    {
        $id   = $this->getUser() ? (int)$this->getUser()->getId() : null;
        $user = $id ? $this->userRepository->getOneById($id) : null;
        if (!$user) {
            return $this->redirectToRoute('homepage');
        }

        $form = $this->createUserForm(ReaderUserType::class, $user, ['id' => $id]);
        if ($request->isMethod(Request::METHOD_POST) &&
            !($errors = $this->formHandler->handleForm($form, $request))
        ) {
            $this->userRepository->updateUser($id, $form->getData());

            return $this->redirectToRoute('profile');
        }

        return $this->render('default/crud/profile/index.html.twig',
            [
                'id'   => $id,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route(path="/author", name="profile.author")
     */
    public function author(Request $request, PaginatorInterface $paginator): Response
    {
        $q     = $request->get('q');
        $p     = $request->query->getInt('p', 1);
        $rows  = $this->bookRepository->getAll($q, ['authorIds' => $this->getUser()->getId()]);
        $books = $paginator->paginate($rows, $p, self::ITEMS_IN_PAGE);

        return $this->render('default/crud/profile/author.html.twig',
            [
                'books' => $books,
            ]
        );
    }

    /**
     * @Route(path="/reader", name="profile.reader")
     */
    public function reader(Request $request, PaginatorInterface $paginator): Response
    {
        $q     = $request->get('q');
        $p     = $request->query->getInt('p', 1);
        $rows  = $this->bookRepository->getAll($q, ['readerIds' => $this->getUser()->getId()]);
        $books = $paginator->paginate($rows, $p, self::ITEMS_IN_PAGE);

        return $this->render('default/crud/profile/reader.html.twig',
            [
                'books' => $books,
            ]
        );
    }

}
