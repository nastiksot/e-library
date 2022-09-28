<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210916071318 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mail_logs ADD updated_at DATETIME DEFAULT NULL, CHANGE status status VARCHAR(15) DEFAULT NULL');
        $this->addSql('CREATE INDEX idx_updated_at ON mail_logs (updated_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX idx_updated_at ON mail_logs');
        $this->addSql('ALTER TABLE mail_logs DROP updated_at, CHANGE status status VARCHAR(15) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
