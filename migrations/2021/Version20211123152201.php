<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211123152201 extends AbstractMigration
{

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE decisions ADD admin_title VARCHAR(255) DEFAULT NULL');
        $this->addSql('UPDATE decisions SET admin_title = CONCAT("Title ID#", id) WHERE parent_decision_id IS NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE decisions DROP admin_title');
    }
}
