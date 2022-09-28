<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211118085715 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE wish_list_product_set_product_replacements (id INT UNSIGNED AUTO_INCREMENT NOT NULL, wish_list_product_set_product_id INT UNSIGNED NOT NULL, product_id INT UNSIGNED NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX fk_wish_list_product_set_product_id (wish_list_product_set_product_id), INDEX fk_product_id (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE wish_list_product_set_product_replacements ADD CONSTRAINT FK_E39D910F65BB150A FOREIGN KEY (wish_list_product_set_product_id) REFERENCES wish_list_product_set_products (id)');
        $this->addSql('ALTER TABLE wish_list_product_set_product_replacements ADD CONSTRAINT FK_E39D910F4584665A FOREIGN KEY (product_id) REFERENCES products (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE wish_list_product_set_product_replacements');
    }
}
