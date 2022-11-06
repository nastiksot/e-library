<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\DataFixtures\Traits\EntityDataFixtureTrait;
use App\Entity\Book\Author;
use App\Entity\Book\Book;
use App\Entity\Book\Category;
use App\Entity\Stock;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

use function random_int;

/**
 * @method Stock createEntity
 */
class StockFixtures extends Fixture implements DependentFixtureInterface
{
    use EntityDataFixtureTrait;

    public function getDependencies()
    {
        return [
            BookFixtures::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= BookFixtures::COUNT_BOOKS; $i++) {
            $data   = $this->createData('stock', $i);
            $entity = $this->createEntity(Stock::class, $data);

            $book   = $this->getReference('book-' . $i);
            $entity->setBook($book);

            $manager->persist($entity);
            $this->addReference('stock-' . $i, $entity);
        }

        // save
        $manager->flush();
    }

    private function createData(string $prefix, int $index): array
    {
        $indexKey = $this->createIndexKey($index);
        $suffix   = ($indexKey ? '-' . $indexKey : '');

        return [
            'quantity' => random_int(100, 100),
            'reserved' => 0,
        ];
    }

}
