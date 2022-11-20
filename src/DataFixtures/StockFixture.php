<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Book\Book;
use App\Entity\Stock;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

use function random_int;

class StockFixture extends Fixture implements DependentFixtureInterface
{
    public static int $counter = 0;

    public function getDependencies()
    {
        return [
            BookFixture::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= BookFixture::$counter; $i++) {
            ++static::$counter;
            /** @var Book $book */
            $book   = $this->getReference('book-' . $i);

            // create
            $entity = (new Stock())
                ->setBook($book)
                ->setQuantity(random_int(100, 100))
                ->setReserved(0);

            // persist
            $manager->persist($entity);
            $this->addReference('stock-' . static::$counter, $entity);
        }

        // save
        $manager->flush();
    }
}
