<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220117110250 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE wish_list_product_set_products ADD alternative_product_id INT UNSIGNED DEFAULT NULL AFTER current_quantity');
        $this->addSql('ALTER TABLE wish_list_product_set_products ADD CONSTRAINT FK_4028132A5D8CBA4B FOREIGN KEY (alternative_product_id) REFERENCES products (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX fk_alternative_product_id ON wish_list_product_set_products (alternative_product_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE wish_list_product_set_products DROP FOREIGN KEY FK_4028132A5D8CBA4B');
        $this->addSql('DROP INDEX fk_alternative_product_id ON wish_list_product_set_products');
        $this->addSql('ALTER TABLE wish_list_product_set_products DROP alternative_product_id');
    }
}
