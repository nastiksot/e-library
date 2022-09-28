<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211214175113 extends AbstractMigration
{

    public function up(Schema $schema): void
    {
        $this->addSql(
            'ALTER TABLE settings_general ADD cart_add_products_url VARCHAR(255) DEFAULT NULL, ADD dealer_search_url VARCHAR(255) DEFAULT NULL'
        );

//        $this->addSql(
//            'UPDATE settings_general SET cart_add_products_url = :cart_add_products_url',
//            [
//                'cart_add_products_url' => 'https://shop.somfy.de/index.php/checkout/cart/directadd?products=',
//            ]
//        );
//
//        $this->addSql(
//            'UPDATE settings_general SET dealer_search_url = :dealer_search_url',
//            [
//                'dealer_search_url' => 'https://www.somfy.de/haendlersuche',
//            ]
//        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE settings_general DROP cart_add_products_url, DROP dealer_search_url');
    }
}
