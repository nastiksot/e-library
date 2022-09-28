<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210929160735 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE decision_product_sets (decision_id INT UNSIGNED NOT NULL, product_set_id INT UNSIGNED NOT NULL, INDEX IDX_2DE64C11BDEE7539 (decision_id), INDEX IDX_2DE64C1172874CBC (product_set_id), PRIMARY KEY(decision_id, product_set_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE decision_product_sets ADD CONSTRAINT FK_2DE64C11BDEE7539 FOREIGN KEY (decision_id) REFERENCES decisions (id)');
        $this->addSql('ALTER TABLE decision_product_sets ADD CONSTRAINT FK_2DE64C1172874CBC FOREIGN KEY (product_set_id) REFERENCES product_sets (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE decision_product_sets');
    }
}
