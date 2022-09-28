<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210817174751 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE filter_groups (
                id INT UNSIGNED AUTO_INCREMENT NOT NULL,

                active TINYINT(1) DEFAULT 1 NOT NULL,
                position INT DEFAULT 0 NOT NULL,

                created_at DATETIME DEFAULT NULL,
                updated_at DATETIME DEFAULT NULL,

                INDEX idx_active (active),
                INDEX idx_position (position),
                INDEX idx_created_at (created_at),
                INDEX idx_updated_at (updated_at),

                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        $this->addSql('
            CREATE TABLE filter_group_translations (
                id INT UNSIGNED AUTO_INCREMENT NOT NULL,
                translatable_id INT UNSIGNED DEFAULT NULL,
                locale VARCHAR(5) NOT NULL,

                title VARCHAR(255) DEFAULT NULL,

                created_at DATETIME DEFAULT NULL,
                updated_at DATETIME DEFAULT NULL,

                INDEX IDX_941A9A52C2AC5D3 (translatable_id),

                UNIQUE INDEX filter_group_translations_unique_translation (translatable_id, locale),

                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        $this->addSql('
            CREATE TABLE filters (
                id INT UNSIGNED AUTO_INCREMENT NOT NULL,
                filter_group_id INT UNSIGNED NOT NULL,

                active TINYINT(1) DEFAULT 1 NOT NULL,
                position INT DEFAULT 0 NOT NULL,

                created_at DATETIME DEFAULT NULL,
                updated_at DATETIME DEFAULT NULL,

                INDEX fk_filter_group_id (filter_group_id),

                INDEX idx_active (active),
                INDEX idx_position (position),
                INDEX idx_created_at (created_at),
                INDEX idx_updated_at (updated_at),

                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        $this->addSql('
            CREATE TABLE filter_translations (
                id INT UNSIGNED AUTO_INCREMENT NOT NULL,
                translatable_id INT UNSIGNED DEFAULT NULL,
                locale VARCHAR(5) NOT NULL,

                title VARCHAR(255) DEFAULT NULL,

                created_at DATETIME DEFAULT NULL,
                updated_at DATETIME DEFAULT NULL,

                INDEX IDX_371A3A102C2AC5D3 (translatable_id),
                UNIQUE INDEX filter_translations_unique_translation (translatable_id, locale),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        $this->addSql('ALTER TABLE filter_group_translations ADD CONSTRAINT FK_941A9A52C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES filter_groups (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE filters ADD CONSTRAINT FK_7877678DC33BDCE7 FOREIGN KEY (filter_group_id) REFERENCES filter_groups (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE filter_translations ADD CONSTRAINT FK_371A3A102C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES filters (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE filter_group_translations DROP FOREIGN KEY FK_941A9A52C2AC5D3');
        $this->addSql('ALTER TABLE filters DROP FOREIGN KEY FK_7877678DC33BDCE7');
        $this->addSql('ALTER TABLE filter_translations DROP FOREIGN KEY FK_371A3A102C2AC5D3');

        $this->addSql('DROP TABLE filter_translations');
        $this->addSql('DROP TABLE filters');
        $this->addSql('DROP TABLE filter_group_translations');
        $this->addSql('DROP TABLE filter_groups');
    }
}
