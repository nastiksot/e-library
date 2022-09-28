<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210928112926 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_alternatives (product_id INT UNSIGNED NOT NULL, alternative_product_id INT UNSIGNED NOT NULL, INDEX IDX_CBCC093F4584665A (product_id), INDEX IDX_CBCC093F5D8CBA4B (alternative_product_id), PRIMARY KEY(product_id, alternative_product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_alternatives ADD CONSTRAINT FK_CBCC093F4584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE product_alternatives ADD CONSTRAINT FK_CBCC093F5D8CBA4B FOREIGN KEY (alternative_product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE products DROP alternative_sku');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE product_alternatives');
        $this->addSql('ALTER TABLE products ADD alternative_sku VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
