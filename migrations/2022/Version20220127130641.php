<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220127130641 extends AbstractMigration
{

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE wish_list_product_set_product_replacements DROP FOREIGN KEY FK_E39D910F4584665A');
        $this->addSql('ALTER TABLE wish_list_product_set_product_replacements DROP FOREIGN KEY FK_E39D910F65BB150A');
        $this->addSql('ALTER TABLE wish_list_product_set_product_replacements ADD CONSTRAINT FK_E39D910F4584665A FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE wish_list_product_set_product_replacements ADD CONSTRAINT FK_E39D910F65BB150A FOREIGN KEY (wish_list_product_set_product_id) REFERENCES wish_list_product_set_products (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE wish_list_product_set_products DROP FOREIGN KEY FK_4028132A4584665A');
        $this->addSql('ALTER TABLE wish_list_product_set_products ADD CONSTRAINT FK_4028132A4584665A FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE wish_list_product_set_product_replacements DROP FOREIGN KEY FK_E39D910F65BB150A');
        $this->addSql('ALTER TABLE wish_list_product_set_product_replacements DROP FOREIGN KEY FK_E39D910F4584665A');
        $this->addSql('ALTER TABLE wish_list_product_set_product_replacements ADD CONSTRAINT FK_E39D910F65BB150A FOREIGN KEY (wish_list_product_set_product_id) REFERENCES wish_list_product_set_products (id)');
        $this->addSql('ALTER TABLE wish_list_product_set_product_replacements ADD CONSTRAINT FK_E39D910F4584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE wish_list_product_set_products DROP FOREIGN KEY FK_4028132A4584665A');
        $this->addSql('ALTER TABLE wish_list_product_set_products ADD CONSTRAINT FK_4028132A4584665A FOREIGN KEY (product_id) REFERENCES products (id)');
    }
}
