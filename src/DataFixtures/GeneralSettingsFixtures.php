<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Contracts\Entity\UserInterface;
use App\DataFixtures\Traits\EntityDataFixtureTrait;
use App\Entity\Settings\GeneralSettings;
use App\Entity\User\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GeneralSettingsFixtures extends Fixture
{
    use EntityDataFixtureTrait;

    public function load(ObjectManager $manager)
    {
        $data   = $this->createData();
        $entity = $this->createEntity(GeneralSettings::class, $data);
        $manager->persist($entity);
        $this->addReference('general-settings', $entity);

//
//        for ($i = 0; $i <= self::COUNT_ADMINS; $i++) {
//            $data   = $this->createData(UserInterface::ROLE_ADMIN, 'admin', $i);
//            $entity = $this->createEntity(User::class, $data);
//            $manager->persist($entity);
//            $this->addReference('admin-' . $i, $entity);
//        }
//
//        for ($i = 0; $i <= self::COUNT_LIBRARIANS; $i++) {
//            $data   = $this->createData(UserInterface::ROLE_LIBRARIAN, 'lib', $i);
//            $entity = $this->createEntity(User::class, $data);
//            $manager->persist($entity);
//            $this->addReference('librarian-' . $i, $entity);
//        }
//
//        for ($i = 0; $i <= self::COUNT_READERS; $i++) {
//            $data   = $this->createData(UserInterface::ROLE_READER, 'reader', $i);
//            $entity = $this->createEntity(User::class, $data);
//            $manager->persist($entity);
//            $this->addReference('reader-' . $i, $entity);
//        }

        // save
        $manager->flush();
    }

    private function createData(): array
    {
        return [
        ];
    }

}
