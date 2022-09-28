<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210825115935 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE `wish_list_product_set_products` (
                `id` INT UNSIGNED AUTO_INCREMENT NOT NULL,
                `wish_list_product_set_id` INT UNSIGNED NOT NULL,
                `product_id` INT UNSIGNED NOT NULL,
                `original_quantity` INT NOT NULL,
                `current_quantity` INT NOT NULL,

                INDEX `fk_wish_list_product_set_id` (`wish_list_product_set_id`),
                INDEX `fk_product_id` (`product_id`),

                PRIMARY KEY(`id`)
            ) DEFAULT CHARACTER SET `utf8mb4` COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        $this->addSql('
            CREATE TABLE `wish_list_product_sets` (
                `id` INT UNSIGNED AUTO_INCREMENT NOT NULL,
                `wish_list_id` INT UNSIGNED NOT NULL,
                `product_set_id` INT UNSIGNED NOT NULL,

                INDEX `fk_wish_list_id` (`wish_list_id`),
                INDEX `fk_product_set_id` (`product_set_id`),

                PRIMARY KEY(`id`)
            ) DEFAULT CHARACTER SET `utf8mb4` COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        $this->addSql('
            CREATE TABLE `wish_lists` (
                `id` INT UNSIGNED AUTO_INCREMENT NOT NULL,
                `user_uid` BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
                `created_at` DATETIME DEFAULT NULL,
                `updated_at` DATETIME DEFAULT NULL,

                INDEX `idx_user_uid` (`user_uid`),
                INDEX `idx_created_at` (`created_at`),
                INDEX `idx_updated_at` (`updated_at`),
                PRIMARY KEY(`id`)
            ) DEFAULT CHARACTER SET `utf8mb4` COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        $this->addSql('ALTER TABLE `wish_list_product_set_products` ADD CONSTRAINT `FK_4028132A45A054EC` FOREIGN KEY (`wish_list_product_set_id`) REFERENCES `wish_list_product_sets` (`id`) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `wish_list_product_set_products` ADD CONSTRAINT `FK_4028132A4584665A` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)');
        $this->addSql('ALTER TABLE `wish_list_product_sets` ADD CONSTRAINT `FK_35AFFCA95973A0F7` FOREIGN KEY (`wish_list_id`) REFERENCES `wish_lists` (`id`) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `wish_list_product_sets` ADD CONSTRAINT `FK_35AFFCA972874CBC` FOREIGN KEY (`product_set_id`) REFERENCES `product_sets` (`id`) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `wish_list_product_set_products` DROP FOREIGN KEY `FK_4028132A45A054EC`');
        $this->addSql('ALTER TABLE `wish_list_product_sets` DROP FOREIGN KEY `FK_35AFFCA95973A0F7`');
        $this->addSql('DROP TABLE `wish_list_product_set_products`');
        $this->addSql('DROP TABLE `wish_list_product_sets`');
        $this->addSql('DROP TABLE `wish_lists`');
    }
}
