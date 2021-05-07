<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Contracts\UserInterface;
use App\Form\Type\User\AuthorUserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/author")
 */
class AuthorController extends AbstractController
{
    /**
     * @Route(path="/list", name="author.list")
     */
    public function index(): Response
    {
        $users = $this->userRepository->getAllAuthors();

        return $this->render('default/crud/author/index.html.twig',
            [
                'users' => $users,
            ]
        );
    }

    /**
     * @Route(path="/add", name="author.add")
     */
    public function add(Request $request): Response
    {
        $form = $this->createUserForm(AuthorUserType::class);
        if ($request->isMethod(Request::METHOD_POST) &&
            !($errors = $this->formHandler->handleForm($form, $request))
        ) {
            $this->userRepository->createUser(UserInterface::ROLE_AUTHOR, $form->getData());
            return $this->redirectToRoute('author.list');
        }

        return $this->render('default/crud/author/add.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route(path="/{id}/edit", name="author.edit")
     */
    public function edit(Request $request, int $id): Response
    {
        $user = $this->userRepository->getOneById($id);
        if (!$user) {
            return $this->redirectToRoute('author.list');
        }

        $form = $this->createUserForm(AuthorUserType::class, $user, ['id' => $id]);
        if ($request->isMethod(Request::METHOD_POST) &&
            !($errors = $this->formHandler->handleForm($form, $request))
        ) {
            $this->userRepository->updateUser($id, $form->getData());

            return $this->redirectToRoute('author.list');
        }

        return $this->render('default/crud/author/edit.html.twig',
            [
                'id'   => $id,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route(path="/{id}/delete", name="author.delete")
     */
    public function delete(int $id): Response
    {
        $this->userRepository->deleteOneById($id);

        return $this->redirectToRoute('author.list');
    }

}
