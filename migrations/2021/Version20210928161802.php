<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210928161802 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE decision_translations (id INT UNSIGNED AUTO_INCREMENT NOT NULL, translatable_id INT UNSIGNED DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_F234F1E62C2AC5D3 (translatable_id), UNIQUE INDEX decision_translations_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE decisions (id INT UNSIGNED AUTO_INCREMENT NOT NULL, parent_decision_id INT UNSIGNED DEFAULT NULL, final TINYINT(1) DEFAULT \'0\' NOT NULL, positive TINYINT(1) DEFAULT \'0\' NOT NULL, position INT DEFAULT 0 NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_638DAA17D6C26749 (parent_decision_id), INDEX idx_position (position), INDEX idx_created_at (created_at), INDEX idx_updated_at (updated_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE decision_translations ADD CONSTRAINT FK_F234F1E62C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES decisions (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE decisions ADD CONSTRAINT FK_638DAA17D6C26749 FOREIGN KEY (parent_decision_id) REFERENCES decisions (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE decision_translations DROP FOREIGN KEY FK_F234F1E62C2AC5D3');
        $this->addSql('ALTER TABLE decisions DROP FOREIGN KEY FK_638DAA17D6C26749');
        $this->addSql('DROP TABLE decision_translations');
        $this->addSql('DROP TABLE decisions');
    }
}
