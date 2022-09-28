<?php

declare(strict_types=1);

namespace App\Controller;

//use RuntimeException;
//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Routing\Annotation\Route;

use App\Form\FormHandler;
use App\Form\Type\User\RegisterUserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends \App\Controller\AbstractController
{
    #[Route(
        path: '/register',
        name: 'register'
    )]
    public function index(Request $request, FormHandler $formHandler): Response
    {
        $form = $formHandler->createForm(RegisterUserType::class);


        if ($request->isMethod(Request::METHOD_POST) &&
            !($errors = $formHandler->handleForm($form, $request))
        ) {
            $data = $form->getData();
            //$this->bookManager->update($id, $data);

            //return $this->redirectToRoute('book.list');
        }


        return $this->render('admin/security/register.html.twig', [
            'form' => $form->createView(),
        ]);
//        return $this->render('default/auth/register.html.twig', ['dealer_uid' => $dealer?->getUid(), 'title' => $title]);
        //return $this->render('default/day-with-somfy.html.twig', ['dealer_uid' => $dealer?->getUid(), 'title' => $title]);

        //return $this->redirectToRoute('web.homepage');
    }
//
//    #[Route(
//        path: '/logout',
//        name: 'default.logout',
//        options: ['expose' => true],
//        methods: ['GET'],
//    )]
//    public function logout(): void
//    {
//        throw new RuntimeException('Should be captured by security component');
//    }
}
