<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211119113934 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE wish_list_product_sets ADD deleted_product_id INT UNSIGNED DEFAULT NULL, ADD replaced_product_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE wish_list_product_sets ADD CONSTRAINT FK_35AFFCA965EAEB14 FOREIGN KEY (deleted_product_id) REFERENCES products (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE wish_list_product_sets ADD CONSTRAINT FK_35AFFCA9512D7A2E FOREIGN KEY (replaced_product_id) REFERENCES products (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_35AFFCA965EAEB14 ON wish_list_product_sets (deleted_product_id)');
        $this->addSql('CREATE INDEX IDX_35AFFCA9512D7A2E ON wish_list_product_sets (replaced_product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE wish_list_product_sets DROP FOREIGN KEY FK_35AFFCA965EAEB14');
        $this->addSql('ALTER TABLE wish_list_product_sets DROP FOREIGN KEY FK_35AFFCA9512D7A2E');
        $this->addSql('DROP INDEX IDX_35AFFCA965EAEB14 ON wish_list_product_sets');
        $this->addSql('DROP INDEX IDX_35AFFCA9512D7A2E ON wish_list_product_sets');
        $this->addSql('ALTER TABLE wish_list_product_sets DROP deleted_product_id, DROP replaced_product_id');
    }
}
