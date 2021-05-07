<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\FormHandler;
use App\Repository\BookRepository;
use App\Repository\UserRepository;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseAbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * @method User|null getUser()
 **/
abstract class AbstractController extends BaseAbstractController
{

    public const ITEMS_IN_PAGE = 1;

    protected FormHandler             $formHandler;
    protected EncoderFactoryInterface $encoderFactory;
    protected UserRepository          $userRepository;
    protected BookRepository          $bookRepository;

    public function __construct(
        FormHandler $formHandler,
        EncoderFactoryInterface $encoderFactory,
        UserRepository $userRepository,
        BookRepository $bookRepository
    ) {
        $this->formHandler    = $formHandler;
        $this->encoderFactory = $encoderFactory;
        $this->userRepository = $userRepository;
        $this->bookRepository = $bookRepository;
    }

    public function getConnection($name = null): Connection
    {
        return $this->getDoctrine()->getConnection($name);
    }


    protected function createUserForm(string $type, array $user = [], array $options = []): FormInterface
    {
        $fields = [
            'first_name',
            'last_name',
            'username',
            'email',
        ];

        $data = (array_intersect_key($user, array_flip($fields)));

        return $this->container->get('form.factory')->create($type, $data, $options);
    }

    protected function createBookForm(string $type, array $user = [], array $options = []): FormInterface
    {
        $fields = [
            'title',
            'authors',
        ];

        $data = (array_intersect_key($user, array_flip($fields)));

        return $this->container->get('form.factory')->create($type, $data, $options);
    }


}
