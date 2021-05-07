<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Contracts\UserInterface;
use App\Form\Type\User\ReaderUserType;
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
}
