<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211119085638 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX idx_excluded ON wish_list_product_set_products');
        $this->addSql('ALTER TABLE wish_list_product_set_products DROP excluded');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE wish_list_product_set_products ADD excluded TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('CREATE INDEX idx_excluded ON wish_list_product_set_products (excluded)');
    }
}
