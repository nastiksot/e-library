<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211125185139 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE slide_translations (id INT UNSIGNED AUTO_INCREMENT NOT NULL, translatable_id INT UNSIGNED DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, sub_title VARCHAR(255) DEFAULT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_159605092C2AC5D3 (translatable_id), UNIQUE INDEX slide_translations_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE slider_translations (id INT UNSIGNED AUTO_INCREMENT NOT NULL, translatable_id INT UNSIGNED DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, sub_title VARCHAR(255) DEFAULT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_ECA13F552C2AC5D3 (translatable_id), UNIQUE INDEX slider_translations_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sliders (id INT UNSIGNED AUTO_INCREMENT NOT NULL, position INT DEFAULT 0 NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, image VARCHAR(255) DEFAULT NULL, INDEX idx_position (position), INDEX idx_created_at (created_at), INDEX idx_updated_at (updated_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE slides (id INT UNSIGNED AUTO_INCREMENT NOT NULL, slider_id INT UNSIGNED DEFAULT NULL, product_set_id INT UNSIGNED DEFAULT NULL, position INT DEFAULT 0 NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, image VARCHAR(255) DEFAULT NULL, youtube_video_url VARCHAR(255) DEFAULT NULL, INDEX idx_position (position), INDEX idx_created_at (created_at), INDEX idx_updated_at (updated_at), INDEX idx_slider_id (slider_id), INDEX idx_product_set_id (product_set_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE slide_translations ADD CONSTRAINT FK_159605092C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES slides (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE slider_translations ADD CONSTRAINT FK_ECA13F552C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES sliders (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE slides ADD CONSTRAINT FK_B8C020912CCC9638 FOREIGN KEY (slider_id) REFERENCES sliders (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE slides ADD CONSTRAINT FK_B8C0209172874CBC FOREIGN KEY (product_set_id) REFERENCES product_sets (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE slider_translations DROP FOREIGN KEY FK_ECA13F552C2AC5D3');
        $this->addSql('ALTER TABLE slides DROP FOREIGN KEY FK_B8C020912CCC9638');
        $this->addSql('ALTER TABLE slide_translations DROP FOREIGN KEY FK_159605092C2AC5D3');
        $this->addSql('DROP TABLE slide_translations');
        $this->addSql('DROP TABLE slider_translations');
        $this->addSql('DROP TABLE sliders');
        $this->addSql('DROP TABLE slides');
    }
}
