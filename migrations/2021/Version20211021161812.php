<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211021161812 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('DROP INDEX `idx_available_online_only` ON `products`');
        $this->addSql('
            ALTER TABLE `products`
                ADD `where_to_buy` VARCHAR(255) NOT NULL COMMENT \'(DC2Type:App\\\\Contracts\\\\Dictionary\\\\WhereToBuy)\',
                DROP `available_online_only`
        ');

        $this->addSql("UPDATE `products` SET `where_to_buy` = 'online_and_retail'");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE `products`
                ADD `available_online_only` TINYINT(1) DEFAULT 0 NOT NULL,
                DROP `where_to_buy`
        ');

        $this->addSql('CREATE INDEX `idx_available_online_only` ON `products` (`available_online_only`)');
    }
}
