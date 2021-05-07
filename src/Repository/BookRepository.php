<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Persistence\ManagerRegistry;

class BookRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function deleteOneById(int $id): void
    {
        $sql  = "DELETE FROM `books` WHERE id = :id";
        $conn = $this->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery(['id' => $id]);
    }

    public function getOneById(int $id): ?array
    {
        $sql  = "SELECT * FROM `books` WHERE id = :id";
        $conn = $this->getConnection();
        $stmt = $conn->prepare($sql);
        return $stmt->executeQuery(['id' => $id])->fetchAssociative();
    }

    public function createBook(array $data): void
    {
        $sql  = "INSERT INTO `books` (`title`, `active`) VALUES  (:title, :active)";
        $conn = $this->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery([
            'title'  => $data['title'],
            'active' => 1,
        ]);

        $id = (int)$conn->lastInsertId();
        $this->updateBookAuthor($id, $data['authors'] ?? []);
    }

    public function updateBook(int $id, array $data): void
    {
        $sql  = "UPDATE `books` SET `title` = :title WHERE id = :id";
        $conn = $this->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery([
            'id'    => $id,
            'title' => $data['title'] ?? null,
        ]);

        $this->updateBookAuthor($id, $data['authors'] ?? []);
    }

    protected function updateBookAuthor(int $id, array $authorIds): void
    {

    }

    public function getAll(): array
    {
        $sql  = "SELECT * FROM `books`";
        $conn = $this->getConnection();
        $stmt = $conn->prepare($sql);

        return $stmt->executeQuery()->fetchAllAssociative();
    }
}
