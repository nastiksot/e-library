<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211221165303 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql("UPDATE `products` SET `where_to_buy` = 'online_and_retail' WHERE `where_to_buy` IS NULL");

        $this->addSql("
            ALTER TABLE `products`
                CHANGE `where_to_buy` `where_to_buy` VARCHAR(255) DEFAULT 'online_and_retail' NOT NULL COMMENT '(DC2Type:App\\\\Contracts\\\\Dictionary\\\\WhereToBuy)'
        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("
            ALTER TABLE `products`
                CHANGE `where_to_buy` `where_to_buy` VARCHAR(255) CHARACTER SET `utf8mb4` DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT '(DC2Type:App\\\\Contracts\\\\Dictionary\\\\WhereToBuy)'
        ");
    }
}
