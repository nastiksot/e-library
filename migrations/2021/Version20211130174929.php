<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211130174929 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE slide_translations');
        $this->addSql('ALTER TABLE slides ADD product_set_active TINYINT(1) DEFAULT \'0\' NOT NULL, ADD product_set_position VARCHAR(255) DEFAULT NULL COMMENT \'(DC2Type:App\\\\Contracts\\\\Dictionary\\\\SlideProductSetPosition)\', DROP youtube_video_url');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE slide_translations (id INT UNSIGNED AUTO_INCREMENT NOT NULL, translatable_id INT UNSIGNED DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, sub_title VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, locale VARCHAR(5) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX slide_translations_unique_translation (translatable_id, locale), INDEX IDX_159605092C2AC5D3 (translatable_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE slide_translations ADD CONSTRAINT FK_159605092C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES slides (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE slides ADD youtube_video_url VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP product_set_active, DROP product_set_position');
    }
}
