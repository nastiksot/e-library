<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211101140314 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE decision_products (decision_id INT UNSIGNED NOT NULL, product_id INT UNSIGNED NOT NULL, INDEX IDX_3E669564BDEE7539 (decision_id), INDEX IDX_3E6695644584665A (product_id), PRIMARY KEY(decision_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE decision_products ADD CONSTRAINT FK_3E669564BDEE7539 FOREIGN KEY (decision_id) REFERENCES decisions (id)');
        $this->addSql('ALTER TABLE decision_products ADD CONSTRAINT FK_3E6695644584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE decisions ADD product_id INT UNSIGNED DEFAULT NULL, ADD action VARCHAR(255) DEFAULT NULL COMMENT \'(DC2Type:App\\\\Contracts\\\\Dictionary\\\\DecisionAction)\'');
        $this->addSql('ALTER TABLE decisions ADD CONSTRAINT FK_638DAA174584665A FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX idx_product_id ON decisions (product_id)');
        $this->addSql('CREATE INDEX idx_action ON decisions (action)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE decision_products');
        $this->addSql('ALTER TABLE decisions DROP FOREIGN KEY FK_638DAA174584665A');
        $this->addSql('DROP INDEX idx_product_id ON decisions');
        $this->addSql('DROP INDEX idx_action ON decisions');
        $this->addSql('ALTER TABLE decisions DROP product_id, DROP action');
    }
}
