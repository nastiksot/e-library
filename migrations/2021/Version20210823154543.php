<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210823154543 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE `product_set_products`
                ADD `id` INT UNSIGNED AUTO_INCREMENT NOT NULL,
                ADD `quantity` INT NOT NULL,
                ADD `created_at` DATETIME DEFAULT NULL,
                ADD `updated_at` DATETIME DEFAULT NULL,

                DROP PRIMARY KEY,
                ADD PRIMARY KEY (`id`)
        ');

        $this->addSql('CREATE INDEX `idx_created_at` ON `product_set_products` (`created_at`)');
        $this->addSql('CREATE INDEX `idx_updated_at` ON `product_set_products` (`updated_at`)');
        $this->addSql('ALTER TABLE `product_set_products` RENAME INDEX `idx_f7a30c72874cbc` TO `fk_product_set_id`');
        $this->addSql('ALTER TABLE `product_set_products` RENAME INDEX `idx_f7a30c4584665a` TO `fk_product_id`');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX `idx_created_at` ON `product_set_products`');
        $this->addSql('DROP INDEX `idx_updated_at` ON `product_set_products`');
        $this->addSql('ALTER TABLE `product_set_products` DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE `product_set_products` DROP `id`, DROP `quantity`, DROP `created_at`, DROP `updated_at`');
        $this->addSql('ALTER TABLE `product_set_products` ADD PRIMARY KEY (`product_set_id`, `product_id`)');
        $this->addSql('ALTER TABLE `product_set_products` RENAME INDEX `fk_product_id` TO `IDX_F7A30C4584665A`');
        $this->addSql('ALTER TABLE `product_set_products` RENAME INDEX `fk_product_set_id` TO `IDX_F7A30C72874CBC`');
    }
}
