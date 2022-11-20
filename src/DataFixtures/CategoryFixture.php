<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Book\Category;
use Doctrine\Persistence\ObjectManager;

class CategoryFixture extends AbstractFixture
{
    public static int $counter = 0;
    public const DATA_FILE = 'Data/Category.txt';

    public function load(ObjectManager $manager)
    {
        $rows = $this->readFile(__DIR__ . '/' . self::DATA_FILE);
        if ($rows) {
            foreach ($rows as $row) {
                ++static::$counter;
                // create
                $entity = (new Category())
                    ->setName($row);

                // persist
                $manager->persist($entity);
                $this->addReference('category-' . static::$counter, $entity);
            }
        }

        // save
        $manager->flush();
    }
}
