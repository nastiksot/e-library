<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Contracts\UserInterface;
use App\Form\Type\User\AdminUserType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route(path="/list", name="admin.list")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $q      = $request->get('q');
        $p      = $request->query->getInt('p', 1);
        $rows   = $this->userRepository->getAllAdmins($q);
        $admins = $paginator->paginate($rows, $p, self::ITEMS_IN_PAGE);

        return $this->render('default/crud/admin/index.html.twig',
            [
                'admins' => $admins,
            ]
        );
    }

    /**
     * @Route(path="/add", name="admin.add")
     */
    public function add(Request $request): Response
    {
        $form = $this->createUserForm(AdminUserType::class);
        if ($request->isMethod(Request::METHOD_POST) &&
            !($errors = $this->formHandler->handleForm($form, $request))
        ) {
            $this->userRepository->createUser(UserInterface::ROLE_ADMIN, $form->getData());
            return $this->redirectToRoute('admin.list');
        }

        return $this->render('default/crud/admin/add.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route(path="/{id}/edit", name="admin.edit")
     */
    public function edit(Request $request, int $id): Response
    {
        $user = $this->userRepository->getOneById($id);
        if (!$user) {
            return $this->redirectToRoute('admin.list');
        }

        $form = $this->createUserForm(AdminUserType::class, $user, ['id' => $id]);
        if ($request->isMethod(Request::METHOD_POST) &&
            !($errors = $this->formHandler->handleForm($form, $request))
        ) {
            $this->userRepository->updateUser($id, $form->getData());

            return $this->redirectToRoute('admin.list');
        }

        return $this->render('default/crud/admin/edit.html.twig',
            [
                'id'   => $id,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route(path="/{id}/delete", name="admin.delete")
     */
    public function delete(int $id): Response
    {
        $this->userRepository->deleteOneById($id);

        return $this->redirectToRoute('admin.list');
    }

}
