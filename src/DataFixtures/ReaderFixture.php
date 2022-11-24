<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Contracts\Entity\UserInterface;
use App\DataFixtures\Traits\EntityDataFixtureTrait;
use App\Entity\Book\Author;
use App\Entity\User\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

use function explode;
use function ucfirst;

class ReaderFixture extends AbstractFixture implements DependentFixtureInterface
{
    public static int $counter = 0;
    public const DATA_FILE = 'Data/Reader.txt';

    public function getDependencies()
    {
        return [
            UserFixture::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        $rows = $this->readFile(__DIR__ . '/' . self::DATA_FILE);
        if ($rows) {
            foreach ($rows as $row) {

//                if (static::$counter >=10) {
//                    continue;
//                }

                ++static::$counter;

                $name  = explode(' ', $row);
                $email = strtolower(str_replace(' ', '-', $row) . '@example.com');

                // create
                $entity = (new User())
                    ->setRoles([UserInterface::ROLE_READER])
                    ->setEmail($email)
                    ->setFirstName(!empty($name[0]) ? ucfirst($name[0]) : null)
                    ->setLastName(!empty($name[1]) ? ucfirst($name[1]) : null)
                    ->setPlainPassword('1111')
                    ->setActive(true);

                // persist
                $manager->persist($entity);
                $this->addReference('reader-' . static::$counter, $entity);
            }
        }

        // save
        $manager->flush();
    }

//    private function createData(string $role, string $prefix, int $index): array
//    {
//        $indexKey = $this->createIndexKey($index);
//        $suffix   = ($indexKey ? '-' . $indexKey : '');
//
//        return [
//            'username'      => $prefix . $suffix,
//            'email'         => $prefix . $suffix . '@example.com',
//            'firstName'     => $prefix . $suffix . 'FirstName',
//            'lastName'      => $prefix . $suffix . 'LastName',
//            'plainPassword' => $prefix . $suffix,
//            'roles'         => [$role],
//            'active'        => true,
//        ];
//    }

}
