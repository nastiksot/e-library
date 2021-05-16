<?php
declare(strict_types=1);

namespace App\Service\Manager;

class BookManager extends AbstractManager
{

    public function query(array $filter = []): array
    {
        $sql    = "
        SELECT books.id AS _id, books.*
        FROM `books`
        LEFT JOIN `authors_books` AS ab ON ab.book_id = books.id
        LEFT JOIN authors AS author ON author.id = ab.author_id
        WHERE 1 ";
        $params = [];

        if (!empty($filter['q'])) {
            $sql             .= " AND (
                books.title         LIKE :query OR
                books.description   LIKE :query OR
                author.first_name   LIKE :query OR
                author.last_name    LIKE :query
            )";
            $params['query'] = '%' . $filter['q'] . '%';
        }

        $sql .= " GROUP BY books.id";

        return [$sql, $params];
    }

    public function create(array $data): int
    {
        $sql = "
        INSERT INTO `books` (
            `title`, `description`
        )
        VALUES  (
            :title, :description
        )";

        $conn = $this->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery([
            'title'       => $data['title'] ?? null,
            'description' => $data['description'] ?? null,
        ]);

        $id = (int)$conn->lastInsertId();
        $this->updateAuthors($id, $data['authors'] ?? []);

        return (int)$conn->lastInsertId();
    }

    public function update(int $id, array $data): int
    {
        $sql  = "UPDATE `books` SET
                `title` = :title,
                `description` = :description
                WHERE id = :id";
        $conn = $this->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery([
            'id'          => $id,
            'title'       => $data['title'] ?? null,
            'description' => $data['description'] ?? null,
        ]);

        $this->updateAuthors($id, $data['authors'] ?? []);

        return $id;
    }

    protected function updateAuthors(int $bookId, array $authorIds): void
    {
        $conn = $this->getConnection();

        // delete authors before add new
        $sql  = "DELETE FROM `authors_books` WHERE book_id = :book_id";
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery(['book_id' => $bookId]);

        // add new authors
        foreach ($authorIds as $authorId) {
            $sql  = "INSERT INTO `authors_books` (`author_id`, `book_id`) VALUES  (:author_id, :book_id)";
            $stmt = $conn->prepare($sql);
            $stmt->executeQuery(['author_id' => $authorId, 'book_id' => $bookId]);
        }
    }

    public function getAuthors(int $bookId): array
    {
        $conn = $this->getConnection();
        $sql  = "SELECT authors.id AS _id, authors.* FROM `authors` WHERE id IN(SELECT author_id FROM authors_books WHERE book_id = :book_id)";
        $stmt = $conn->prepare($sql);

        return $stmt->executeQuery(['book_id' => $bookId])->fetchAllAssociativeIndexed();
    }


    public function get(int $id): ?array
    {
        $sql  = "SELECT * FROM `books` WHERE id = :id";
        $conn = $this->getConnection();
        $stmt = $conn->prepare($sql);

        $result = $stmt->executeQuery(['id' => $id])->fetchAssociative();
        if ($result) {
            $result['authors'] = $this->getAuthors($id);
        }

        return $result ?: null;
    }

    public function delete(int $id): void
    {
        $sql  = "DELETE FROM `books` WHERE id = :id";
        $conn = $this->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery(['id' => $id]);
    }

}
