<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210929090030 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX idx_show_price ON products');
        $this->addSql('ALTER TABLE products ADD price_on_request TINYINT(1) DEFAULT \'0\' NOT NULL, DROP show_price');
        $this->addSql('CREATE INDEX idx_price_on_request ON products (price_on_request)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX idx_price_on_request ON products');
        $this->addSql('ALTER TABLE products ADD show_price TINYINT(1) DEFAULT \'1\' NOT NULL, DROP price_on_request');
        $this->addSql('CREATE INDEX idx_show_price ON products (show_price)');
    }
}
