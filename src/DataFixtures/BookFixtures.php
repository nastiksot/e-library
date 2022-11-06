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
 * @method Book createEntity
 */
class BookFixtures extends Fixture implements DependentFixtureInterface
{
    use EntityDataFixtureTrait;

    public const COUNT_BOOKS = 1;

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            AuthorFixtures::class,
            CategoryFixtures::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= self::COUNT_BOOKS; $i++) {
            $data   = $this->createData('book', $i);
            $entity = $this->createEntity(Book::class, $data);

            $authors = $this->getRandomAuthors();
            foreach ($authors as $author) {
                $entity->addAuthor($author);
            }

            $categories = $this->getRandomCategories();
            foreach ($categories as $category) {
                $entity->addCategory($category);
            }

            $manager->persist($entity);
            $this->addReference('book-' . $i, $entity);
        }

        // save
        $manager->flush();

        // update stoks
        $stoks = $manager->getRepository(Stock::class)->findAll();
        foreach ($stoks as $stok) {
            $stok->setQuantity(random_int(100, 100));
            $manager->persist($stok);
        }

        // save stocks
        $manager->flush();
    }

    private function createData(string $prefix, int $index): array
    {
        $indexKey = $this->createIndexKey($index);
        $suffix   = ($indexKey ? '-' . $indexKey : '');

        return [
            'name'        => $prefix . $suffix . '-Name',
            'description' => $prefix . $suffix . '-Description',
        ];
    }

    /** @return Author[] */
    private function getRandomAuthors(): array
    {
        $result = [];
        $max    = random_int(1, 3);
        for ($i = 1; $i <= $max; $i++) {
            $referenceId   = 'author-' . random_int(1, AuthorFixtures::COUNT_AUTHORS);
            $result[] = $this->getReference($referenceId);
        }

        return $result;
    }

    /** @return Category[] */
    private function getRandomCategories(): array
    {
        $result = [];
        $max    = random_int(1, 5);
        for ($i = 1; $i <= $max; $i++) {
            $referenceId   = 'category-' . random_int(1, CategoryFixtures::COUNT_CATEGORIES);
            $result[] = $this->getReference($referenceId);
        }

        return $result;
    }

}
