<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220120160516 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql("
            ALTER TABLE `product_set_products`
                ADD `product_type` varchar(255) NOT NULL DEFAULT 'regular'
                    COMMENT '(DC2Type:App\\\\Contracts\\\\Dictionary\\\\ProductSetProductType)'
                    AFTER `quantity`
        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `product_set_products` DROP `product_type`');
    }
}
