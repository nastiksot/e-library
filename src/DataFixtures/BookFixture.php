<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Book\Author;
use App\Entity\Book\Book;
use App\Entity\Book\Category;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

use function random_int;
use function ucfirst;

/**
 * @URL https://www.generatormix.com/generators/names%20and%20usernames
 */
class BookFixture extends AbstractFixture implements DependentFixtureInterface
{
    public static int $counter = 0;
    public const DATA_FILE = 'Data/Book.txt';

    public function getDependencies()
    {
        return [
            AuthorFixture::class,
            CategoryFixture::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        $rows = $this->readFile(__DIR__ . '/' . self::DATA_FILE);
        if ($rows) {
            foreach ($rows as $row) {
                ++static::$counter;
                // create
                $entity = (new Book())
                    ->setName(ucfirst($row));

                $authors = $this->getRandomAuthors();
                foreach ($authors as $author) {
                    $entity->addAuthor($author);
                }

                $categories = $this->getRandomCategories();
                foreach ($categories as $category) {
                    $entity->addCategory($category);
                }

                // persist
                $manager->persist($entity);
                $this->addReference('book-' . static::$counter, $entity);
            }
        }

        // save
        $manager->flush();
    }

    /** @return Author[] */
    private function getRandomAuthors(): array
    {
        $result = [];
        $max    = random_int(1, 3);
        for ($i = 1; $i <= $max; $i++) {
            $counter     = (int)AuthorFixture::$counter;
            $referenceId = 'author-' . random_int(1, $counter);
            $result[]    = $this->getReference($referenceId);
        }

        return $result;
    }

    /** @return Category[] */
    private function getRandomCategories(): array
    {
        $result = [];
        $max    = random_int(1, 5);
        for ($i = 1; $i <= $max; $i++) {
            $counter     = (int)CategoryFixture::$counter;
            $referenceId = 'category-' . random_int(1, $counter);
            $result[]    = $this->getReference($referenceId);
        }

        return $result;
    }

}
