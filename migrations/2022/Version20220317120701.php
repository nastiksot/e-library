<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220317120701 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE wish_lists ADD `name` VARCHAR(255) DEFAULT NULL;');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE wish_lists DROP `name`;');
    }
}
