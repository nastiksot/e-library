<?php
declare(strict_types=1);

namespace App\Service\Manager;

use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UserManager extends AbstractManager
{

    protected EncoderFactoryInterface $encoderFactory;

    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    public function query(array $filter = []): array
    {
        $sql    = "SELECT users.id AS _id, users.* FROM `users` WHERE 1 ";
        $params = [];

        if (!empty($filter['role'])) {
            $sql            .= " AND (users.roles LIKE :role)";
            $params['role'] = '%' . $filter['role'] . '%';
        }

        if (!empty($filter['q'])) {
            $sql             .= " AND (
                users.username      LIKE :query OR
                users.email         LIKE :query OR
                users.first_name    LIKE :query OR
                users.last_name     LIKE :query
            )";
            $params['query'] = '%' . $filter['q'] . '%';
        }

        return [$sql, $params];
    }

    public function create(array $data): int
    {
        $salt    = bin2hex(random_bytes(12));
        $encoder = $this->encoderFactory->getEncoder(User::class);

        $sql = "
        INSERT INTO `users` (
            `first_name`, `last_name`,
            `username`, `password`, `email`, `salt`, `roles`, `active`
        )
        VALUES  (
            :first_name, :last_name,
            :username, :password, :email, :salt, :roles, :active
        )";

        $conn = $this->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery([
            'first_name' => $data['first_name'] ?? null,
            'last_name'  => $data['last_name'] ?? null,
            'username'   => $data['username'] ?? null,
            'password'   => $encoder->encodePassword($data['password'], $salt),
            'email'      => $data['email'] ?? null,
            'salt'       => $salt,
            'roles'      => json_encode([$data['role'] ?? null]),
            'active'     => 1,
        ]);

        return (int)$conn->lastInsertId();
    }

    public function update(int $id, array $data): int
    {
        $sql  = "UPDATE `users` SET
                `first_name` = :first_name,
                `last_name` = :last_name,
                `username` = :username,
                `email` = :email
                WHERE id = :id";
        $conn = $this->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery([
            'id'         => $id,
            'first_name' => $data['first_name'] ?? null,
            'last_name'  => $data['last_name'] ?? null,
            'username'   => $data['username'] ?? null,
            'email'      => $data['email'] ?? null,
        ]);

        // update password
        if ($data['password']) {
            $salt    = $data['salt'];
            $encoder = $this->encoderFactory->getEncoder(User::class);
            $sql     = "UPDATE `users` SET `password` = :password WHERE id = :id";
            $conn    = $this->getConnection();
            $stmt    = $conn->prepare($sql);
            $stmt->executeQuery([
                'id'       => $id,
                'password' => $encoder->encodePassword($data['password'], $salt),
            ]);
        }

        return $id;
    }

    public function get(int $id): ?array
    {
        $sql  = "SELECT * FROM `users` WHERE id = :id";
        $conn = $this->getConnection();
        $stmt = $conn->prepare($sql);

        $result = $stmt->executeQuery(['id' => $id])->fetchAssociative();

        return $result ?: null;
    }

    public function delete(int $id): void
    {
        $sql  = "DELETE FROM `users` WHERE id = :id";
        $conn = $this->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery(['id' => $id]);
    }

}
