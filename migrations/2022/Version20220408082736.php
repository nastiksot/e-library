<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220408082736 extends AbstractMigration
{

    public function up(Schema $schema): void
    {
        $this->addSql(
            'ALTER TABLE countries ADD cart_add_products_url VARCHAR(255) NOT NULL, ADD dealer_search_url VARCHAR(255) NOT NULL;'
        );

        $this->addSql(
            'UPDATE countries
                    SET cart_add_products_url = "https://shop.somfy.de/index.php/checkout/cart/directadd?products=",
                        dealer_search_url = "https://www.somfy.de/haendlersuche/smart-home/";'
        );

        $this->addSql(
            'ALTER TABLE settings_general DROP cart_add_products_url, DROP dealer_search_url;'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(
            'ALTER TABLE settings_general ADD cart_add_products_url VARCHAR(255) DEFAULT NULL, ADD dealer_search_url VARCHAR(255) DEFAULT NULL;'
        );

        $this->addSql(
            'UPDATE settings_general
             SET cart_add_products_url = "https://shop.somfy.de/index.php/checkout/cart/directadd?products=",
                 dealer_search_url = "https://www.somfy.de/haendlersuche";'
        );

        $this->addSql('ALTER TABLE countries DROP cart_add_products_url, DROP dealer_search_url;');
    }
}
