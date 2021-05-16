<?php

namespace App\DataFixtures;

use App\Entity\Contracts\UserInterface;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public const ADMIN_1 = 'admin-1';

    public const LIBRARIAN_1 = 'librarian-1';
    public const LIBRARIAN_2 = 'librarian-2';
    public const LIBRARIAN_3 = 'librarian-3';

    public const READER_1 = 'reader-1';
    public const READER_2 = 'reader-2';
    public const READER_3 = 'reader-3';


    public function load(ObjectManager $manager)
    {
        $admin = $this->createEntity('admin', UserInterface::ROLE_ADMIN);

        $librarian1 = $this->createEntity('librarian1', UserInterface::ROLE_LIBRARIAN);
        $librarian2 = $this->createEntity('librarian2', UserInterface::ROLE_LIBRARIAN);
        $librarian3 = $this->createEntity('librarian3', UserInterface::ROLE_LIBRARIAN);

        $reader1 = $this->createEntity('reader1', UserInterface::ROLE_READER);
        $reader2 = $this->createEntity('reader2', UserInterface::ROLE_READER);
        $reader3 = $this->createEntity('reader3', UserInterface::ROLE_READER);

        $this->addReference(self::ADMIN_1, $admin);
        $this->addReference(self::LIBRARIAN_1, $librarian1);
        $this->addReference(self::LIBRARIAN_2, $librarian2);
        $this->addReference(self::LIBRARIAN_3, $librarian3);
        $this->addReference(self::READER_1, $reader1);
        $this->addReference(self::READER_2, $reader2);
        $this->addReference(self::READER_3, $reader3);

        // save
        $manager->persist($admin);
        $manager->persist($librarian1);
        $manager->persist($librarian2);
        $manager->persist($librarian3);
        $manager->persist($reader1);
        $manager->persist($reader2);
        $manager->persist($reader3);
        $manager->flush();
    }

    private function createEntity(string $username, string $role): User
    {
        return (new User())
            ->setUsername($username)
            ->setFirstName($username . ' first name')
            ->setLastName($username . ' last name')
            ->setEmail($username . '@example.com')
            ->setPlainPassword($username)
            ->setRoles([$role])
            ->setActive(true);
    }

}
