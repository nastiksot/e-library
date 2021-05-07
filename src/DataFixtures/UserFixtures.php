<?php

namespace App\DataFixtures;

use App\Entity\Contracts\UserInterface;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{

    public const AUTHOR_1 = 'author-1';
    public const AUTHOR_2 = 'author-2';
    public const AUTHOR_3 = 'author-3';
    public const READER_1 = 'reader-1';
    public const READER_2 = 'reader-2';
    public const READER_3 = 'reader-3';


    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
    }

    private function createUser(string $username, string $role): User
    {
        $user = new User();
        $user->setUsername($username)
            ->setFirstName($username . ' first name')
            ->setLastName($username . ' last name')
            ->setActive(true)
            ->setEmail($username . '@example.com')
            ->setPlainPassword($username)
            ->setRoles([$role]);

        return $user;
    }

    private function loadUsers(ObjectManager $manager): void
    {
        $admin   = $this->createUser('admin', UserInterface::ROLE_ADMIN);
        $author1 = $this->createUser('author1', UserInterface::ROLE_AUTHOR);
        $author2 = $this->createUser('author2', UserInterface::ROLE_AUTHOR);
        $author3 = $this->createUser('author3', UserInterface::ROLE_AUTHOR);
        $reader1 = $this->createUser('reader1', UserInterface::ROLE_READER);
        $reader2 = $this->createUser('reader2', UserInterface::ROLE_READER);
        $reader3 = $this->createUser('reader3', UserInterface::ROLE_READER);

        $this->addReference(self::AUTHOR_1, $author1);
        $this->addReference(self::AUTHOR_2, $author2);
        $this->addReference(self::AUTHOR_3, $author3);
        $this->addReference(self::READER_1, $reader1);
        $this->addReference(self::READER_2, $reader2);
        $this->addReference(self::READER_3, $reader3);

        $manager->persist($admin);
        $manager->persist($author1);
        $manager->persist($author2);
        $manager->persist($author3);
        $manager->persist($reader1);
        $manager->persist($reader2);
        $manager->persist($reader3);
        $manager->flush();


    }

}
