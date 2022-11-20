<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Reading;
use Carbon\Carbon;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ReadingFixture extends AbstractFixture implements DependentFixtureInterface
{
    public const COUNT_READING = 100;

    public function getDependencies()
    {
        return [
            StockFixture::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        return;
        for ($i = 1; $i <= 5; $i++) {
            $data   = $this->createData($i, 'super-admin-', UserFixture::COUNT_SUPER_ADMINS);
            $entity = $this->createEntity(Reading::class, $data);
            $manager->persist($entity);
            $this->addReference('reading-super-admin-' . $i, $entity);
        }

        for ($i = 1; $i <= 5; $i++) {
            $data   = $this->createData($i, 'admin-', UserFixture::COUNT_ADMINS);
            $entity = $this->createEntity(Reading::class, $data);
            $manager->persist($entity);
            $this->addReference('reading-admin-' . $i, $entity);
        }

        for ($i = 1; $i <= 5; $i++) {
            $data   = $this->createData($i, 'librarian-', UserFixture::COUNT_LIBRARIANS);
            $entity = $this->createEntity(Reading::class, $data);
            $manager->persist($entity);
            $this->addReference('reading-librarian-' . $i, $entity);
        }

        for ($i = 1; $i <= self::COUNT_READING; $i++) {
            $data   = $this->createData($i, 'reader-', UserFixture::COUNT_READERS);
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

        $bookReferenceId   = 'book-' . random_int(1, BookFixture::COUNT_BOOKS);
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
