<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\DataFixtures\Traits\EntityDataFixtureTrait;
use App\Entity\Reading;
use Carbon\Carbon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ReadingFixtures extends Fixture implements DependentFixtureInterface
{
    use EntityDataFixtureTrait;

    public const COUNT_READING = 100;

    public function getDependencies()
    {
        return [
            BookFixtures::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        return;
        for ($i = 1; $i <= 5; $i++) {
            $data   = $this->createData($i, 'super-admin-', UserFixtures::COUNT_SUPER_ADMINS);
            $entity = $this->createEntity(Reading::class, $data);
            $manager->persist($entity);
            $this->addReference('reading-super-admin-' . $i, $entity);
        }

        for ($i = 1; $i <= 5; $i++) {
            $data   = $this->createData($i, 'admin-', UserFixtures::COUNT_ADMINS);
            $entity = $this->createEntity(Reading::class, $data);
            $manager->persist($entity);
            $this->addReference('reading-admin-' . $i, $entity);
        }

        for ($i = 1; $i <= 5; $i++) {
            $data   = $this->createData($i, 'librarian-', UserFixtures::COUNT_LIBRARIANS);
            $entity = $this->createEntity(Reading::class, $data);
            $manager->persist($entity);
            $this->addReference('reading-librarian-' . $i, $entity);
        }

        for ($i = 1; $i <= self::COUNT_READING; $i++) {
            $data   = $this->createData($i, 'reader-', UserFixtures::COUNT_READERS);
            $entity = $this->createEntity(Reading::class, $data);
            $manager->persist($entity);
            $this->addReference('reading-' . $i, $entity);
        }

        // save
        $manager->flush();
    }

    private function createData(int $index, string $readerReference, int $maxReference): array
    {
        $indexKey = $this->createIndexKey($index);
        $suffix   = ($indexKey ? '-' . $indexKey : '');

        $bookReferenceId   = 'book-' . random_int(1, BookFixtures::COUNT_BOOKS);
        $readerReferenceId = $readerReference . random_int(1, $maxReference);

        return [
            'quantity' => random_int(1, 2),
            'book'     => $this->getReference($bookReferenceId),
            'user'     => $this->getReference($readerReferenceId),
            'startAt'  => Carbon::today()->addDays(5),
            'endAt'    => Carbon::today()->addMonth(1),
        ];
    }


}
