<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\DataFixtures\Traits\EntityDataFixtureTrait;
use App\Entity\Settings\GeneralSettings;
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

        // save
        $manager->flush();
    }

    private function createData(): array
    {
        return [
            'penalty'      => 75.12,
            'penaltyColor' => '#fcaf3e',
            'expireColor'  => '#edd400',
        ];
    }

}
