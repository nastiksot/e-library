<?php
declare(strict_types=1);

namespace App\Service\Manager;

use App\Entity\Order;
use DateTime;

class OrderManager extends AbstractManager
{


    protected ReadingManager $readingManager;

    public function __construct(
        ReadingManager $readingManager
    ) {
        $this->readingManager = $readingManager;
    }

    public function query(array $filter = []): array
    {
        $sql    = "
        SELECT orders.id AS _id, orders.*
        FROM `orders`
        LEFT JOIN `books` AS book ON book.id = orders.book_id
        LEFT JOIN `authors_books` AS ab ON ab.book_id = book.id
        LEFT JOIN `authors` AS author ON author.id = ab.author_id
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

        if (!empty($filter['status'])) {
            $sql              .= " AND (orders.status = :status) ";
            $params['status'] = $filter['status'];
        }


        $sql .= " GROUP BY orders.id";
        $sql .= " ORDER BY orders.id ASC , orders.created_at ASC ";

        return [$sql, $params];
    }

    public function create(array $data): int
    {
        return 0;
//        $sql = "
//        INSERT INTO `books` (
//            `title`, `description`, `quantity`
//        )
//        VALUES  (
//            :title, :description, :quantity
//        )";
//
//        $conn = $this->getConnection();
//        $stmt = $conn->prepare($sql);
//        $stmt->executeQuery([
//            'title'       => $data['title'] ?? null,
//            'description' => $data['description'] ?? null,
//            'quantity'    => (int)$data['quantity'] ?: 0,
//        ]);
//
//        $id = (int)$conn->lastInsertId();
//        $this->updateAuthors($id, $data['authors'] ?? []);
//
//        return $id;
    }

    public function update(int $id, array $data): int
    {
        return 0;
//        $sql  = "UPDATE `books` SET
//                `title` = :title,
//                `description` = :description,
//                `quantity`  = :quantity
//                WHERE id = :id";
//        $conn = $this->getConnection();
//        $stmt = $conn->prepare($sql);
//        $stmt->executeQuery([
//            'id'          => $id,
//            'title'       => $data['title'] ?? null,
//            'description' => $data['description'] ?? null,
//            'quantity'    => (int)$data['quantity'] ?: 0,
//        ]);
//
//        $this->updateAuthors($id, $data['authors'] ?? []);
//
//        return $id;
    }

//    protected function updateAuthors(int $bookId, array $authorIds): void
//    {
//        $conn = $this->getConnection();
//
//        // delete authors before add new
//        $sql  = "DELETE FROM `authors_books` WHERE book_id = :book_id";
//        $stmt = $conn->prepare($sql);
//        $stmt->executeQuery(['book_id' => $bookId]);
//
//        // add new authors
//        foreach ($authorIds as $authorId) {
//            $sql  = "INSERT INTO `authors_books` (`author_id`, `book_id`) VALUES  (:author_id, :book_id)";
//            $stmt = $conn->prepare($sql);
//            $stmt->executeQuery(['author_id' => $authorId, 'book_id' => $bookId]);
//        }
//    }
//
//    public function getAuthors(int $bookId): array
//    {
//        $conn = $this->getConnection();
//        $sql  = "SELECT authors.id AS _id, authors.* FROM `authors` WHERE id IN(SELECT author_id FROM authors_books WHERE book_id = :book_id)";
//        $stmt = $conn->prepare($sql);
//
//        return $stmt->executeQuery(['book_id' => $bookId])->fetchAllAssociativeIndexed();
//    }


    public function get(int $id): ?array
    {
        $sql  = "SELECT * FROM `orders` WHERE id = :id";
        $conn = $this->getConnection();
        $stmt = $conn->prepare($sql);

        return $stmt->executeQuery(['id' => $id])->fetchAssociative();
//        $result = $stmt->executeQuery(['id' => $id])->fetchAssociative();
//        if ($result) {
//            $result['authors'] = $this->getAuthors($id);
//        }
//
//        return $result ?: null;
    }

    public function delete(int $id): void
    {
        $sql  = "DELETE FROM `orders` WHERE id = :id";
        $conn = $this->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery(['id' => $id]);
    }

    public function status($id, array $data): int
    {
        $sql  = "UPDATE `orders` SET
                `status` = :status
                WHERE id = :id";
        $conn = $this->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery([
            'id'     => $id,
            'status' => $data['status'] ?? null,
        ]);

        return $id;
    }

    public function cancel(int $id): int
    {
        $this->status($id, ['status' => Order::STATUS_CANCELED]);
        return $id;
    }

    public function done(int $id): int
    {
        $this->status($id, ['status' => Order::STATUS_DONE]);
        $order = $this->get($id);
        $data = [
            'book_id'      => $order['book_id'],
            'quantity'     => $order['quantity'],
            'user_id'      => $order['user_id'],
            'reading_type' => $order['reading_type'],
            'start_at'     => $order['start_at'] ? DateTime::createFromFormat('Y-m-d', $order['start_at']) : null,
            'end_at'       => $order['end_at'] ? DateTime::createFromFormat('Y-m-d', $order['end_at']) : null,
        ];
        $this->readingManager->create($data);

        return $id;
    }

    public function open(int $id): int
    {
        $this->status($id, ['status' => Order::STATUS_OPEN]);
        return $id;
    }

//    public function order(int $id, array $data): int
//    {
//        $sql = "
//        INSERT INTO `orders` (
//            `book_id`, `quantity`, `status`, `created_at`
//        )
//        VALUES  (
//            :book_id, :quantity, :status, :created_at
//        )";
//
//        $conn = $this->getConnection();
//        $stmt = $conn->prepare($sql);
//        $stmt->executeQuery([
//            'book_id'    => $id ?? null,
//            'quantity'   => $data['quantity'] ?? null,
//            'status'     => Order::STATUS_OPEN,
//            'created_at' => date('Y-m-d H:i:s'),
//        ]);
//
//        $orderId = (int)$conn->lastInsertId();
//
//        return $orderId;
//    }
}
