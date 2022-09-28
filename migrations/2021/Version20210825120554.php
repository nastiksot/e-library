<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210825120554 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE `wish_list_product_set_products`
                ADD `created_at` DATETIME DEFAULT NULL,
                ADD `updated_at` DATETIME DEFAULT NULL
        ');

        $this->addSql('CREATE INDEX `idx_created_at` ON `wish_list_product_set_products` (`created_at`)');
        $this->addSql('CREATE INDEX `idx_updated_at` ON `wish_list_product_set_products` (`updated_at`)');

        $this->addSql('
            ALTER TABLE `wish_list_product_sets`
                ADD `created_at` DATETIME DEFAULT NULL,
                ADD `updated_at` DATETIME DEFAULT NULL
        ');
        $this->addSql('CREATE INDEX `idx_created_at` ON `wish_list_product_sets` (`created_at`)');
        $this->addSql('CREATE INDEX `idx_updated_at` ON `wish_list_product_sets` (`updated_at`)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX `idx_created_at` ON `wish_list_product_set_products`');
        $this->addSql('DROP INDEX `idx_updated_at` ON `wish_list_product_set_products`');
        $this->addSql('ALTER TABLE `wish_list_product_set_products` DROP `created_at`, DROP `updated_at`');
        $this->addSql('DROP INDEX `idx_created_at` ON `wish_list_product_sets`');
        $this->addSql('DROP INDEX `idx_updated_at` ON `wish_list_product_sets`');
        $this->addSql('ALTER TABLE `wish_list_product_sets` DROP `created_at`, DROP `updated_at`');
    }
}
