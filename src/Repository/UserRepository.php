<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Contracts\UserInterface;
use App\Entity\User;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UserRepository extends AbstractRepository implements UserLoaderInterface
{

    protected EncoderFactoryInterface $encoderFactory;

    public function __construct(
        ManagerRegistry $registry,
        EncoderFactoryInterface $encoderFactory
    ) {
        parent::__construct($registry, User::class);
        $this->encoderFactory = $encoderFactory;
    }

    public function getAllAdmins(): array
    {
        return $this->getUsersByRole(UserInterface::ROLE_ADMIN);
    }
    public function getAllAuthors(): array
    {
        return $this->getUsersByRole(UserInterface::ROLE_AUTHOR);
    }

    public function getAllReaders(): array
    {
        return $this->getUsersByRole(UserInterface::ROLE_READER);
    }

    protected function getUsersByRole(string $role): array
    {
        $sql  = "SELECT * FROM `users` WHERE `roles` LIKE '%" . $role . "%'";
        $conn = $this->getConnection();
        $stmt = $conn->prepare($sql);

        return $stmt->executeQuery()->fetchAllAssociative();
    }

    public function deleteOneById(int $id): void
    {
        $sql  = "DELETE FROM `users` WHERE id = :id";
        $conn = $this->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery(['id' => $id]);
    }

    public function getOneById(int $id): ?array
    {
        $sql  = "SELECT * FROM `users` WHERE id = :id";
        $conn = $this->getConnection();
        $stmt = $conn->prepare($sql);
        return $stmt->executeQuery(['id' => $id])->fetchAssociative();
    }

    public function createUser(string $role, array $data): void
    {
        $salt    = bin2hex(random_bytes(12));
        $encoder = $this->encoderFactory->getEncoder(User::class);

        $sql  = "INSERT INTO `users` (`username`, `password`, `email`, `salt`, `roles`, `active`)
            VALUES  (:username, :password, :email, :salt, :roles, :active)";
        $conn = $this->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery([
            'username' => $data['username'] ?? null,
            'password' => $encoder->encodePassword($data['password'], $salt),
            'email'    => $data['email'] ?? null,
            'salt'     => $salt,
            'roles'    => json_encode([$role]),
            'active'   => 1,
        ]);
    }

    public function updateUser(int $id, array $data): void
    {
        // update user data
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
    }

    /**
     * @param string $username
     *
     * @return null|User|UserInterface
     */
    public function loadUserByUsername($username): ?\Symfony\Component\Security\Core\User\UserInterface
    {
        try {
            return $this->createQueryBuilder('u')
                ->where('u.username = :username OR u.email = :email')
                ->setParameters(['username' => $username, 'email' => $username])
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }
}
