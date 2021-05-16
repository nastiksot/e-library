<?php
declare(strict_types=1);

namespace App\Controller\CRUD\User;

use App\Controller\AbstractController;
use App\Entity\Contracts\UserInterface;
use App\Form\Type\User\ReaderUserType;
use App\Service\Manager\UserManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/crud/reader")
 */
class ReaderUserController extends AbstractController
{

    protected UserManager $userManager;

    public function __construct(
        UserManager $userManager
    ) {
        $this->userManager = $userManager;
    }

    /**
     * @Route(path="/list", name="crud.reader.list")
     */
    public function index(Request $request): Response
    {
        $filter = array_merge($request->query->all(), ['role' => UserInterface::ROLE_READER]);
        $users  = $this->userManager->paginate($filter);

        return $this->render(
            'default/crud/user/reader/index.html.twig',
            [
                'users' => $users,
            ]
        );
    }

    /**
     * @Route(path="/add", name="crud.reader.add")
     */
    public function add(Request $request): Response
    {
        $form = $this->userManager->form(ReaderUserType::class);
        if ($request->isMethod(Request::METHOD_POST) &&
            !($errors = $this->userManager->handleForm($form, $request))
        ) {
            $data         = $form->getData();
            $data['role'] = UserInterface::ROLE_READER;
            $this->userManager->create($data);

            return $this->redirectToRoute('crud.reader.list');
        }

        return $this->render(
            'default/crud/user/reader/add.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route(path="/{id}/edit", name="crud.reader.edit")
     */
    public function edit(Request $request, int $id): Response
    {
        $data = $this->userManager->get($id);
        unset($data['password']);
        $form = $this->userManager->form(ReaderUserType::class, $data ?? [], ['id' => $id]);
        if ($request->isMethod(Request::METHOD_POST) &&
            !($errors = $this->userManager->handleForm($form, $request))
        ) {
            $data = $form->getData();
            $this->userManager->update($id, $data);

            return $this->redirectToRoute('crud.reader.list');
        }

        return $this->render(
            'default/crud/user/reader/edit.html.twig',
            [
                'id'   => $id,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route(path="/{id}/delete", name="crud.reader.delete")
     */
    public function delete(int $id): RedirectResponse
    {
        $this->userManager->delete($id);

        return $this->redirectToReferer('crud.reader.list');
    }

}
