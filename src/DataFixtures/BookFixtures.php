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
            AuthorFixtures::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        $author1 = $this->getReference(AuthorFixtures::AUTHOR_1);
        $author2 = $this->getReference(AuthorFixtures::AUTHOR_2);
        $author3 = $this->getReference(AuthorFixtures::AUTHOR_3);
        $author4 = $this->getReference(AuthorFixtures::AUTHOR_4);
        $author5 = $this->getReference(AuthorFixtures::AUTHOR_5);

        $book1 = $this->createEntity('Book-1', 10);
        $book1->addAuthor($author1);

        $book2 = $this->createEntity('Book-2', 20);
        $book2->addAuthor($author2);
        $book2->addAuthor($author3);

        $book3 = $this->createEntity('Book-3', 30);
        $book3->addAuthor($author1);
        $book3->addAuthor($author4);
        $book3->addAuthor($author5);

        $book4 = $this->createEntity('Book-4', 40);
        $book4->addAuthor($author2);
        $book4->addAuthor($author4);

        $book5 = $this->createEntity('Book-5', 50);
        $book5->addAuthor($author4);
        $book5->addAuthor($author5);

        $book6 = $this->createEntity('Book-6', 60);
        $book6->addAuthor($author2);
        $book6->addAuthor($author3);

        $book7 = $this->createEntity('Book-7', 70);
        $book7->addAuthor($author1);

        $book8 = $this->createEntity('Book-8', 80);
        $book8->addAuthor($author2);

        $book9 = $this->createEntity('Book-9', 90);
        $book9->addAuthor($author3);

        $book10 = $this->createEntity('Book-10', 100);
        $book10->addAuthor($author4);

        // save
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

    private function createEntity(string $title, int $quantity): Book
    {
        return (new Book())
            ->setTitle($title)
            ->setDescription($title . ' description')
            ->setQuantity($quantity);
    }

}
