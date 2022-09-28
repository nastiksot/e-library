<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210823145103 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE `product_sets` (
                `id` INT UNSIGNED AUTO_INCREMENT NOT NULL,
                `icon` VARCHAR(255) NOT NULL,
                `active` TINYINT(1) DEFAULT 1 NOT NULL,
                `created_at` DATETIME DEFAULT NULL,
                `updated_at` DATETIME DEFAULT NULL,

                INDEX `idx_active` (`active`),
                INDEX `idx_created_at` (`created_at`),
                INDEX `idx_updated_at` (`updated_at`),

                PRIMARY KEY(`id`)
            ) DEFAULT CHARACTER SET `utf8mb4` COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        $this->addSql('
            CREATE TABLE `product_set_translations` (
                `id` INT UNSIGNED AUTO_INCREMENT NOT NULL,

                `translatable_id` INT UNSIGNED DEFAULT NULL,
                `locale` VARCHAR(5) NOT NULL,

                `title` VARCHAR(255) DEFAULT NULL,

                `created_at` DATETIME DEFAULT NULL,
                `updated_at` DATETIME DEFAULT NULL,

                INDEX `IDX_857B11D82C2AC5D3` (`translatable_id`),

                UNIQUE INDEX `product_set_translations_unique_translation` (`translatable_id`, `locale`),

                PRIMARY KEY(`id`)
            ) DEFAULT CHARACTER SET `utf8mb4` COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');


        $this->addSql('
            CREATE TABLE `product_set_products` (
                `product_set_id` INT UNSIGNED NOT NULL,
                `product_id` INT UNSIGNED NOT NULL,

                INDEX `IDX_F7A30C72874CBC` (`product_set_id`),
                INDEX `IDX_F7A30C4584665A` (`product_id`),

                PRIMARY KEY(`product_set_id`, `product_id`)
            ) DEFAULT CHARACTER SET `utf8mb4` COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );


        $this->addSql('
            CREATE TABLE `product_set_filters` (
                `product_set_id` INT UNSIGNED NOT NULL,
                `filter_id` INT UNSIGNED NOT NULL,

                INDEX `IDX_53F41F7572874CBC` (`product_set_id`),
                INDEX `IDX_53F41F75D395B25E` (`filter_id`),

                PRIMARY KEY(`product_set_id`, `filter_id`)
            ) DEFAULT CHARACTER SET `utf8mb4` COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');


        $this->addSql('ALTER TABLE `product_set_translations` ADD CONSTRAINT `FK_857B11D82C2AC5D3` FOREIGN KEY (`translatable_id`) REFERENCES `product_sets` (`id`) ON DELETE CASCADE');

        $this->addSql('ALTER TABLE `product_set_products` ADD CONSTRAINT `FK_F7A30C72874CBC` FOREIGN KEY (`product_set_id`) REFERENCES `product_sets` (`id`) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `product_set_products` ADD CONSTRAINT `FK_F7A30C4584665A` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE');

        $this->addSql('ALTER TABLE `product_set_filters` ADD CONSTRAINT `FK_53F41F7572874CBC` FOREIGN KEY (`product_set_id`) REFERENCES `product_sets` (`id`) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `product_set_filters` ADD CONSTRAINT `FK_53F41F75D395B25E` FOREIGN KEY (`filter_id`) REFERENCES `filters` (`id`) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `product_set_translations` DROP FOREIGN KEY `FK_857B11D82C2AC5D3`');
        $this->addSql('ALTER TABLE `product_set_products` DROP FOREIGN KEY `FK_F7A30C72874CBC`');
        $this->addSql('ALTER TABLE `product_set_filters` DROP FOREIGN KEY `FK_53F41F7572874CBC`');

        $this->addSql('DROP TABLE `product_set_products`');
        $this->addSql('DROP TABLE `product_set_filters`');

        $this->addSql('DROP TABLE `product_set_translations`');
        $this->addSql('DROP TABLE `product_sets`');
    }
}
