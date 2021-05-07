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
        $sql  = "INSERT INTO `books` (`title`) VALUES  (:title)";
        $conn = $this->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery([
            'title' => $data['title'],
        ]);

        $id = (int)$conn->lastInsertId();
        $this->updateBookAuthors($id, $data['authors'] ?? []);
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

        $this->updateBookAuthors($id, $data['authors'] ?? []);
    }

    protected function updateBookAuthors(int $id, array $authorIds): void
    {
        $conn = $this->getConnection();

        // delete authors before add new
        $sql  = "DELETE FROM `books_authors` WHERE book_id = :book_id";
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery(['book_id' => $id]);

        // add new authors
        foreach ($authorIds as $authorId) {
            $sql  = "INSERT INTO `books_authors` (`user_id`, `book_id`) VALUES  (:user_id, :book_id)";
            $stmt = $conn->prepare($sql);
            $stmt->executeQuery(['user_id' => $authorId, 'book_id' => $id]);
        }
    }

    public function getAuthorsByBookId(int $bookId): array
    {
        $conn = $this->getConnection();
        $sql  = "SELECT users.id AS _id, users.* FROM `users` WHERE id IN(SELECT user_id FROM books_authors WHERE book_id = :book_id)";
        $stmt = $conn->prepare($sql);

        return $stmt->executeQuery(['book_id' => $bookId])->fetchAllAssociativeIndexed();
    }


    public function getReaderByReaderId(int $readerId): array
    {
        $conn = $this->getConnection();
        $sql  = "SELECT users.id AS _id, users.* FROM `users` WHERE id = :reader_id";
        $stmt = $conn->prepare($sql);

        return $stmt->executeQuery(['reader_id' => $readerId])->fetchAssociative();
    }

    public function createBooksQuery(string $q = null, array $sqlParams = []): array
    {
        $params = [];
        $sql    = "
        SELECT books.id AS _id, books.*
        FROM `books`
        LEFT JOIN books_authors AS ba ON ba.book_id = books.id
        LEFT JOIN users AS author ON author.id = ba.user_id
        LEFT JOIN users AS reader ON reader.id = books.reader_id
        WHERE 1";
        if ($q) {
            $sql             .= " AND (
                books.title         LIKE :query OR
                author.username     LIKE :query OR
                author.email        LIKE :query OR
                author.first_name   LIKE :query OR
                author.last_name    LIKE :query OR
                reader.username     LIKE :query OR
                reader.email        LIKE :query OR
                reader.first_name   LIKE :query OR
                reader.last_name    LIKE :query
            )";
            $params['query'] = '%' . $q . '%';
        }

        if (array_key_exists('authorIds', $sqlParams)) {
            $sql .= " AND author.id " . (is_array($sqlParams['authorIds'])
                    ? " IN(" . implode(', ', $sqlParams['authorIds']) . ") "
                    : " = " . $sqlParams['authorIds']);
        }

        if (array_key_exists('readerIds', $sqlParams)) {
            $sql .= " AND books.reader_id " . (is_array($sqlParams['readerIds'])
                    ? " IN(" . implode(', ', $sqlParams['readerIds']) . ") "
                    : " = " . $sqlParams['readerIds']);
        }


        $sql .= " GROUP BY books.id";

        return [$sql, $params];
    }

    public function getAll(string $q = null, array $params = []): array
    {
        $conn = $this->getConnection();
        [$sql, $params] = $this->createBooksQuery($q, $params);
        $stmt   = $conn->prepare($sql);
        $result = $stmt->executeQuery($params)->fetchAllAssociativeIndexed();

        foreach ($result as $key => $book) {
            $result[$key]['authors'] = $this->getAuthorsByBookId((int)$book['id']);
            $result[$key]['reader']  = $book['reader_id'] ? $this->getReaderByReaderId((int)$book['reader_id']) : [];
        }

        return $result;
    }
}
