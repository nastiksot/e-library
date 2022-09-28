<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211110152652 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql("ALTER TABLE `wish_list_product_set_products` ADD `excluded` TINYINT(1) DEFAULT '0' NOT NULL");
        $this->addSql('CREATE INDEX `idx_excluded` ON `wish_list_product_set_products` (`excluded`)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX `idx_excluded` ON `wish_list_product_set_products`');
        $this->addSql('ALTER TABLE `wish_list_product_set_products` DROP `excluded`');
    }
}
