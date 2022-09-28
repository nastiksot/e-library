<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210816145645 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE menu (
                id INT UNSIGNED AUTO_INCREMENT NOT NULL,
                type VARCHAR(255) NOT NULL,
                link_page_id INT UNSIGNED DEFAULT NULL,
                position INT DEFAULT 0 NOT NULL,
                active TINYINT(1) DEFAULT 1 NOT NULL,
                link_type VARCHAR(10) DEFAULT NULL,
                link VARCHAR(255) DEFAULT NULL,
                link_target VARCHAR(255) DEFAULT NULL,
                no_follow TINYINT(1) DEFAULT 0 NOT NULL,
                css_class VARCHAR(255) DEFAULT NULL,

                created_at DATETIME DEFAULT NULL,
                updated_at DATETIME DEFAULT NULL,

                INDEX fk_link_page_id (link_page_id),
                INDEX idx_position (position),
                INDEX idx_active (active),
                INDEX idx_created_at (created_at),
                INDEX idx_updated_at (updated_at),

                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        $this->addSql('
            CREATE TABLE menu_translations (
                id INT UNSIGNED AUTO_INCREMENT NOT NULL,
                translatable_id INT UNSIGNED DEFAULT NULL,

                title VARCHAR(255) DEFAULT NULL,

                created_at DATETIME DEFAULT NULL,
                updated_at DATETIME DEFAULT NULL,

                locale VARCHAR(5) NOT NULL,
                type VARCHAR(255) NOT NULL,

                INDEX IDX_B9B52B222C2AC5D3 (translatable_id),
                UNIQUE INDEX menu_translations_unique_translation (translatable_id, locale),

                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        $this->addSql('
            CREATE TABLE pages (
                id INT UNSIGNED AUTO_INCREMENT NOT NULL,
                type VARCHAR(255) NOT NULL,
                slug VARCHAR(128) DEFAULT NULL,
                og_image VARCHAR(1000) DEFAULT NULL,
                redirect VARCHAR(255) DEFAULT NULL,

                created_at DATETIME DEFAULT NULL,
                updated_at DATETIME DEFAULT NULL,

                UNIQUE INDEX uniq_slug (slug),

                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        $this->addSql('
            CREATE TABLE page_translations (
                id INT UNSIGNED AUTO_INCREMENT NOT NULL,
                translatable_id INT UNSIGNED DEFAULT NULL,

                meta_title VARCHAR(255) DEFAULT NULL,
                meta_keywords VARCHAR(1000) DEFAULT NULL,
                meta_description VARCHAR(1000) DEFAULT NULL,
                og_title VARCHAR(255) DEFAULT NULL,
                og_description LONGTEXT DEFAULT NULL,
                title VARCHAR(255) DEFAULT NULL,
                content LONGTEXT DEFAULT NULL,

                created_at DATETIME DEFAULT NULL,
                updated_at DATETIME DEFAULT NULL,

                locale VARCHAR(5) NOT NULL,
                type VARCHAR(255) NOT NULL,

                INDEX IDX_78AB76C92C2AC5D3 (translatable_id),
                UNIQUE INDEX page_translations_unique_translation (translatable_id, locale),

                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');


        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A9371203911 FOREIGN KEY (link_page_id) REFERENCES pages (id) ON DELETE SET NULL');

        $this->addSql('ALTER TABLE menu_translations ADD CONSTRAINT FK_B9B52B222C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE page_translations ADD CONSTRAINT FK_78AB76C92C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES pages (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE page_translations DROP FOREIGN KEY FK_78AB76C92C2AC5D3');
        $this->addSql('ALTER TABLE menu_translations DROP FOREIGN KEY FK_B9B52B222C2AC5D3');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A9371203911');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE menu_translations');
        $this->addSql('DROP TABLE page_translations');
        $this->addSql('DROP TABLE pages');
    }
}
