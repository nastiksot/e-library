<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Contracts\UserInterface;
use App\Form\Type\User\ReaderUserType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/reader")
 */
class ReaderController extends AbstractController
{
    /**
     * @Route(path="/list", name="reader.list")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $q       = $request->get('q');
        $p       = $request->query->getInt('p', 1);
        $rows    = $this->userRepository->getAllReaders($q);
        $readers = $paginator->paginate($rows, $p, self::ITEMS_IN_PAGE);

        return $this->render('default/crud/reader/index.html.twig',
            [
                'readers' => $readers,
            ]
        );
    }

    /**
     * @Route(path="/add", name="reader.add")
     */
    public function add(Request $request): Response
    {
        $form = $this->createUserForm(ReaderUserType::class);
        if ($request->isMethod(Request::METHOD_POST) &&
            !($errors = $this->formHandler->handleForm($form, $request))
        ) {
            $this->userRepository->createUser(UserInterface::ROLE_READER, $form->getData());
            return $this->redirectToRoute('reader.list');
        }

        return $this->render('default/crud/reader/add.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route(path="/{id}/edit", name="reader.edit")
     */
    public function edit(Request $request, int $id): Response
    {
        $user = $this->userRepository->getOneById($id);
        if (!$user) {
            return $this->redirectToRoute('reader.list');
        }

        $form = $this->createUserForm(ReaderUserType::class, $user, ['id' => $id]);
        if ($request->isMethod(Request::METHOD_POST) &&
            !($errors = $this->formHandler->handleForm($form, $request))
        ) {
            $this->userRepository->updateUser($id, $form->getData());

            return $this->redirectToRoute('reader.list');
        }

        return $this->render('default/crud/reader/edit.html.twig',
            [
                'id'   => $id,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route(path="/{id}/delete", name="reader.delete")
     */
    public function delete(int $id): Response
    {
        $this->userRepository->deleteOneById($id);

        return $this->redirectToRoute('reader.list');
    }

}
