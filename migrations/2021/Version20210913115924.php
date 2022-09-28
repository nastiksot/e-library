<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

use function array_keys;
use function date;
use function implode;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210913115924 extends AbstractMigration
{

    private array $users = [
        [
            // login: admin
            // password: admin
            'username'                     => 'admin',
            'password'                     => '$argon2id$v=19$m=65536,t=4,p=1$gpHcK7icTc5EjjdJvzHW0Q$3DCa82lw8Z7xyyofnsXQEVuqSjCevEIg+9yVM5SuP8s',
            'email'                        => 'admin@example.com',
            'salt'                         => '557e36fb601a157743a2af5f',
            'roles'                        => '["ROLE_SUPER_ADMIN"]',
            'google_authenticator_enabled' => 0,
            'google_authenticator_token'   => null,
            'active'                       => 1,
        ],
        [
            // login: developer
            // password: developer
            'username'                     => 'developer',
            'password'                     => '$argon2id$v=19$m=65536,t=4,p=1$KwbopNzzobT7U4Yni4X+YQ$vUJggYQ2KzqJ2hAyuwQNqRr267WZqz35YroTQeEBmw8',
            'email'                        => 'developer@example.com',
            'salt'                         => '550235f116c177207a211a40',
            'roles'                        => '["ROLE_SUPER_ADMIN"]',
            'google_authenticator_enabled' => 0,
            'google_authenticator_token'   => null,
            'active'                       => 1,
        ],

    ];

    private function createInsertSQL(
        string $table,
        array &$data,
        ?bool $isCreatedAt = false,
        ?bool $isUpdatedAt = false
    ): string {
        if ($isCreatedAt) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        if ($isUpdatedAt) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }

        return 'INSERT INTO `' . $table . '`
        (`' . implode('`, `', array_keys($data)) . '`)
        VALUES
        (:' . implode(', :', array_keys($data)) . ')';
    }


    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(
            'CREATE TABLE users (id INT UNSIGNED AUTO_INCREMENT NOT NULL, username VARCHAR(255) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, salt VARCHAR(255) NOT NULL, roles JSON NOT NULL, google_authenticator_enabled TINYINT(1) DEFAULT \'0\' NOT NULL, google_authenticator_token VARCHAR(255) DEFAULT NULL, locale VARCHAR(5) DEFAULT \'de\' NOT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX idx_active (active), INDEX idx_created_at (created_at), INDEX idx_updated_at (updated_at), UNIQUE INDEX uniq_username (username), UNIQUE INDEX uniq_email (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql('ALTER TABLE menu ADD link_title VARCHAR(255) DEFAULT NULL');


        // add users
        foreach ($this->users as $user) {
            $sql = $this->createInsertSQL('users', $user, true, true);
            $this->addSql($sql, $user);
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE users');
        $this->addSql('ALTER TABLE menu DROP link_title');
    }
}
