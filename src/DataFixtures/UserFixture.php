<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Contracts\Entity\UserInterface;
use App\DataFixtures\Traits\EntityDataFixtureTrait;
use App\Entity\Book\Author;
use App\Entity\User\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use function explode;

class UserFixture extends AbstractFixture
{
    public static int $counterSuperAdmins = 0;
    public static int $counterAdmins      = 0;
    public static int $counterLibrarians  = 0;

    public const COUNT_SUPER_ADMINS = 1;
    public const COUNT_ADMINS       = 1;
    public const COUNT_LIBRARIANS   = 1;
    public const COUNT_READERS      = 1;

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= self::COUNT_SUPER_ADMINS; $i++) {
            ++static::$counterSuperAdmins;
            $data   = $this->createData(UserInterface::ROLE_SUPER_ADMIN, 'super-admin', $i);
            $entity = $this->createEntity(User::class, $data);
            $manager->persist($entity);
            $this->addReference('super-admin' . ($i > 1 ? '-' . $i : ''), $entity);
        }

        for ($i = 1; $i <= self::COUNT_ADMINS; $i++) {
            ++static::$counterAdmins;
            $data   = $this->createData(UserInterface::ROLE_ADMIN, 'admin', $i);
            $entity = $this->createEntity(User::class, $data);
            $manager->persist($entity);
            $this->addReference('admin' . ($i > 1 ? '-' . $i : ''), $entity);
        }

        for ($i = 1; $i <= self::COUNT_LIBRARIANS; $i++) {
            ++static::$counterLibrarians;
            $data   = $this->createData(UserInterface::ROLE_LIBRARIAN, 'librarian', $i);
            $entity = $this->createEntity(User::class, $data);
            $manager->persist($entity);
            $this->addReference('librarian' . ($i > 1 ? '-' . $i : ''), $entity);
        }

        // save
        $manager->flush();
    }

    private function createData(string $role, string $prefix, int $index): array
    {
        $indexKey = $this->createIndexKey($index);
        $suffix   = ($indexKey ? '-' . $indexKey : '');

        return [
            'username'      => $prefix . $suffix,
            'email'         => $prefix . $suffix . '@example.com',
            'firstName'     => $prefix . $suffix . 'FirstName',
            'lastName'      => $prefix . $suffix . 'LastName',
            'plainPassword' => $prefix . $suffix,
            'roles'         => [$role],
            'active'        => true,
        ];
    }

}
