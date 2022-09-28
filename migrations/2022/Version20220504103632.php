<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220504103632 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
            ALTER TABLE `sliders`
                CHANGE `file_mime_type` `file_mime_type` VARCHAR(255) NULL
                    COMMENT '(DC2Type:App\\\\Contracts\\\\Dictionary\\\\FileMimeType)';
        ");
        $this->addSql("
            ALTER TABLE `sliders`
                CHANGE `file_mobile_mime_type` `file_mobile_mime_type` VARCHAR(255) NULL
                    COMMENT '(DC2Type:App\\\\Contracts\\\\Dictionary\\\\FileMimeType)';
        ");

        $this->addSql("
            ALTER TABLE `slides`
                CHANGE `file_mime_type` `file_mime_type` VARCHAR(255) NULL
                    COMMENT '(DC2Type:App\\\\Contracts\\\\Dictionary\\\\FileMimeType)';
        ");
        $this->addSql("
            ALTER TABLE `slides`
                CHANGE `file_mobile_mime_type` `file_mobile_mime_type` VARCHAR(255) NULL
                    COMMENT '(DC2Type:App\\\\Contracts\\\\Dictionary\\\\FileMimeType)';
        ");



        //////////////////////////////////////////////////////



        $this->addSql('
        CREATE TABLE descriptive_panel_translations (
            id INT UNSIGNED AUTO_INCREMENT NOT NULL,
            translatable_id INT UNSIGNED DEFAULT NULL,
            title VARCHAR(255) DEFAULT NULL,
            description LONGTEXT DEFAULT NULL,
            file VARCHAR(255) DEFAULT NULL,
            file_mime_type VARCHAR(255) DEFAULT NULL COMMENT \'(DC2Type:App\\\\Contracts\\\\Dictionary\\\\FileMimeType)\',
            created_at DATETIME DEFAULT NULL,
            updated_at DATETIME DEFAULT NULL,
            locale VARCHAR(5) NOT NULL,
            INDEX IDX_EF3D9A862C2AC5D3 (translatable_id),
            UNIQUE INDEX descriptive_panel_translations_unique_translation (translatable_id, locale),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('
        CREATE TABLE descriptive_panels (
            id INT UNSIGNED AUTO_INCREMENT NOT NULL,
            pages LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\',
            position INT DEFAULT 0 NOT NULL, active TINYINT(1) DEFAULT 1 NOT NULL,
            created_at DATETIME DEFAULT NULL,
            updated_at DATETIME DEFAULT NULL,
            INDEX idx_position (position),
            INDEX idx_active (active),
            INDEX idx_created_at (created_at),
            INDEX idx_updated_at (updated_at),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('
        ALTER TABLE descriptive_panel_translations
            ADD CONSTRAINT FK_EF3D9A862C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES descriptive_panels (id) ON DELETE CASCADE
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql("
            ALTER TABLE `sliders`
                CHANGE `file_mime_type` `file_mime_type` VARCHAR(255) NULL
                    COMMENT '(DC2Type:App\\\\Contracts\\\\Dictionary\\\\SliderFileMimeType)';
        ");
        $this->addSql("
            ALTER TABLE `sliders`
                CHANGE `file_mobile_mime_type` `file_mobile_mime_type` VARCHAR(255) NULL
                    COMMENT '(DC2Type:App\\\\Contracts\\\\Dictionary\\\\SliderFileMimeType)';
        ");

        $this->addSql("
            ALTER TABLE `slides`
                CHANGE `file_mime_type` `file_mime_type` VARCHAR(255) NULL
                    COMMENT '(DC2Type:App\\\\Contracts\\\\Dictionary\\\\SliderFileMimeType)';
        ");
        $this->addSql("
            ALTER TABLE `slides`
                CHANGE `file_mobile_mime_type` `file_mobile_mime_type` VARCHAR(255) NULL
                    COMMENT '(DC2Type:App\\\\Contracts\\\\Dictionary\\\\SliderFileMimeType)';
        ");



        //////////////////////////////////////////////////////



        $this->addSql('ALTER TABLE descriptive_panel_translations DROP FOREIGN KEY FK_EF3D9A862C2AC5D3');
        $this->addSql('DROP TABLE descriptive_panel_translations');
        $this->addSql('DROP TABLE descriptive_panels');
    }
}
