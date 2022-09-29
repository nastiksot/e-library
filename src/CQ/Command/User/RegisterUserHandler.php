<?php

declare(strict_types=1);

namespace App\CQ\Command\User;

use App\Contracts\Entity\UserInterface;
use App\CQ\Command\CommandHandlerInterface;

use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;

class RegisterUserHandler implements CommandHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function __invoke(RegisterUserCommand $command): User
    {
        // create
        $user = (new User())
            ->setEmail($command->getEmail())
            ->setFirstName($command->getFirstName())
            ->setLastName($command->getLastName())
            ->setPlainPassword($command->getPassword())
            ->setRoles([UserInterface::ROLE_READER]);

        // save
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}
