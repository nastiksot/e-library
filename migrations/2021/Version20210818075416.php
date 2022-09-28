<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210818075416 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE `countries` (
                `id` INT UNSIGNED AUTO_INCREMENT NOT NULL,

                `code` VARCHAR(255) NOT NULL,
                `active` TINYINT(1) DEFAULT 1 NOT NULL,

                `created_at` DATETIME DEFAULT NULL,
                `updated_at` DATETIME DEFAULT NULL,

                INDEX `idx_code` (`code`),
                INDEX `idx_active` (`active`),
                INDEX `idx_created_at` (`created_at`),
                INDEX `idx_updated_at` (`updated_at`),

                PRIMARY KEY(`id`)
            ) DEFAULT CHARACTER SET `utf8mb4` COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        $this->addSql('
            CREATE TABLE `country_translations` (
                `id` INT UNSIGNED AUTO_INCREMENT NOT NULL,

                `translatable_id` INT UNSIGNED DEFAULT NULL,
                `locale` VARCHAR(5) NOT NULL,

                `title` VARCHAR(255) DEFAULT NULL,

                `created_at` DATETIME DEFAULT NULL,
                `updated_at` DATETIME DEFAULT NULL,

                INDEX `IDX_CA1456952C2AC5D3` (`translatable_id`),
                UNIQUE INDEX `country_translations_unique_translation` (`translatable_id`, `locale`),

                PRIMARY KEY(`id`)
            ) DEFAULT CHARACTER SET `utf8mb4` COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        $this->addSql('ALTER TABLE `country_translations` ADD CONSTRAINT `FK_CA1456952C2AC5D3` FOREIGN KEY (`translatable_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `country_translations` DROP FOREIGN KEY `FK_CA1456952C2AC5D3`');
        $this->addSql('DROP TABLE `country_translations`');
        $this->addSql('DROP TABLE `countries`');
    }
}
