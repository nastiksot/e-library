<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210831155415 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_translations ADD name VARCHAR(255) DEFAULT NULL, ADD tip VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE products ADD alternative_sku VARCHAR(255) DEFAULT NULL, ADD show_price TINYINT(1) DEFAULT \'1\' NOT NULL, ADD available_online_only TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('CREATE INDEX idx_show_price ON products (show_price)');
        $this->addSql('CREATE INDEX idx_available_online_only ON products (available_online_only)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_translations DROP name, DROP tip');
        $this->addSql('DROP INDEX idx_show_price ON products');
        $this->addSql('DROP INDEX idx_available_online_only ON products');
        $this->addSql('ALTER TABLE products DROP alternative_sku, DROP show_price, DROP available_online_only');
    }
}
