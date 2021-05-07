<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture implements DependentFixtureInterface
{

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        $this->loadBooks($manager);
    }

    private function loadBooks(ObjectManager $manager): void
    {
        $author1 = $this->getReference(UserFixtures::AUTHOR_1);
        $author2 = $this->getReference(UserFixtures::AUTHOR_2);
        $author3 = $this->getReference(UserFixtures::AUTHOR_3);

        $reader1 = $this->getReference(UserFixtures::READER_1);
        $reader2 = $this->getReference(UserFixtures::READER_2);
        $reader3 = $this->getReference(UserFixtures::READER_3);

        $book1 = (new Book())->setTitle('Book 1');
        $book1->setReader($reader1);
        $book1->addAuthor($author1);
        $book1->addAuthor($author2);

        $book2 = (new Book())->setTitle('Book 2');
        $book2->setReader($reader1);
        $book2->addAuthor($author2);
        $book2->addAuthor($author3);

        $book3 = (new Book())->setTitle('Book 3');
        $book3->setReader($reader1);
        $book3->addAuthor($author3);
        $book3->addAuthor($author1);

        $book4 = (new Book())->setTitle('Book 4');
        $book4->setReader($reader2);
        $book4->addAuthor($author1);
        $book4->addAuthor($author2);

        $book5 = (new Book())->setTitle('Book 5');
        $book5->setReader($reader3);
        $book5->addAuthor($author2);
        $book5->addAuthor($author3);

        $book6 = (new Book())->setTitle('Book 6');
        $book6->addAuthor($author3);
        $book6->addAuthor($author1);

        $book7 = (new Book())->setTitle('Book 7');
        $book7->addAuthor($author1);
        $book7->addAuthor($author2);

        $book8 = (new Book())->setTitle('Book 8');
        $book8->addAuthor($author1);

        $book9 = (new Book())->setTitle('Book 9');
        $book9->addAuthor($author2);

        $book10 = (new Book())->setTitle('Book 10');
        $book10->addAuthor($author3);

        $manager->persist($book1);
        $manager->persist($book2);
        $manager->persist($book3);
        $manager->persist($book4);
        $manager->persist($book5);
        $manager->persist($book6);
        $manager->persist($book7);
        $manager->persist($book8);
        $manager->persist($book9);
        $manager->persist($book10);
        $manager->flush();
    }

}
