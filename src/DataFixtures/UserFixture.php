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
    public static int $counterReaders     = 0;

    public const COUNT_SUPER_ADMINS = 1;
    public const COUNT_ADMINS       = 1;
    public const COUNT_LIBRARIANS   = 1;
    public const COUNT_READERS      = 1;

    public const DATA_READER_FILE = 'Data/Reader.txt';

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

        for ($i = 1; $i <= self::COUNT_READERS; $i++) {
            ++static::$counterReaders;
            $data   = $this->createData(UserInterface::ROLE_READER, 'reader', $i);
            $entity = $this->createEntity(User::class, $data);
            $manager->persist($entity);
            $this->addReference('reader' . ($i > 1 ? '-' . $i : ''), $entity);
        }

        $rows = $this->readFile(__DIR__ . '/' . self::DATA_READER_FILE);
        if ($rows) {
            foreach ($rows as $row) {
                ++static::$counterReaders;

                $name  = explode(' ', $row);
                $email = strtolower(str_replace(' ', '-', $row) . '@example.com');

                // create
                $entity = (new User())
                    ->setRoles([UserInterface::ROLE_READER])
                    ->setEmail($email)
                    ->setFirstName($name[0] ?? null)
                    ->setLastName($name[1] ?? null)
                    ->setPlainPassword('1111')
                    ->setActive(true);

                // persist
                $manager->persist($entity);
                $this->addReference('reader-' . static::$counterReaders, $entity);
            }
        }

        // save
        $manager->flush();

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
