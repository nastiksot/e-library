<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220224080923 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('
            CREATE TABLE dealer_request_comments (
                id INT UNSIGNED AUTO_INCREMENT NOT NULL,
                dealer_request_id INT UNSIGNED NOT NULL,
                created_at DATETIME DEFAULT NULL,
                updated_at DATETIME DEFAULT NULL,
                message TEXT DEFAULT NULL,
                INDEX idx_dealer_request_id (dealer_request_id),
                INDEX idx_created_at (created_at),
                INDEX idx_updated_at (updated_at),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dealer_request_comments ADD CONSTRAINT FK_2F65EAB146E8DEF5 FOREIGN KEY (dealer_request_id) REFERENCES dealer_requests (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE dealer_request_comments');
    }
}
