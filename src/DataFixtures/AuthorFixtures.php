<?php

namespace App\DataFixtures;

use App\Entity\Author;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AuthorFixtures extends Fixture
{
    public const AUTHOR_1 = 'author-1';
    public const AUTHOR_2 = 'author-2';
    public const AUTHOR_3 = 'author-3';
    public const AUTHOR_4 = 'author-4';
    public const AUTHOR_5 = 'author-5';

    public function load(ObjectManager $manager)
    {
        $author1 = $this->createEntity(['firstName' => 'Author-1-first-name', 'lastName' => 'LastName-1']);
        $author2 = $this->createEntity(['firstName' => 'Author-2-first-name', 'lastName' => 'LastName-2']);
        $author3 = $this->createEntity(['firstName' => 'Author-3-first-name', 'lastName' => 'LastName-3']);
        $author4 = $this->createEntity(['firstName' => 'Author-4-first-name', 'lastName' => 'LastName-4']);
        $author5 = $this->createEntity(['firstName' => 'Author-5-first-name', 'lastName' => 'LastName-5']);

        $this->addReference(self::AUTHOR_1, $author1);
        $this->addReference(self::AUTHOR_2, $author2);
        $this->addReference(self::AUTHOR_3, $author3);
        $this->addReference(self::AUTHOR_4, $author4);
        $this->addReference(self::AUTHOR_5, $author5);

        // save
        $manager->persist($author1);
        $manager->persist($author2);
        $manager->persist($author3);
        $manager->persist($author4);
        $manager->persist($author5);
        $manager->flush();
    }

    private function createEntity(array $data): Author
    {
        return (new Author())
            ->setFirstName($data['firstName'] ?? null)
            ->setLastName($data['lastName'] ?? null);
    }

}
