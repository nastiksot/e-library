<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211112162734 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP TABLE decision_product_sets');
        $this->addSql('ALTER TABLE product_set_translations ADD description LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE TABLE decision_product_sets (decision_id INT UNSIGNED NOT NULL, product_set_id INT UNSIGNED NOT NULL, INDEX IDX_2DE64C1172874CBC (product_set_id), INDEX IDX_2DE64C11BDEE7539 (decision_id), PRIMARY KEY(decision_id, product_set_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE decision_product_sets ADD CONSTRAINT FK_2DE64C1172874CBC FOREIGN KEY (product_set_id) REFERENCES product_sets (id)');
        $this->addSql('ALTER TABLE decision_product_sets ADD CONSTRAINT FK_2DE64C11BDEE7539 FOREIGN KEY (decision_id) REFERENCES decisions (id)');
        $this->addSql('ALTER TABLE product_set_translations DROP description');
    }
}
