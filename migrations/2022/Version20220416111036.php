<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220416111036 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE messages CHANGE COLUMN `default_value` `default_value` VARCHAR(400) NOT NULL;');
        $this->addSql('ALTER TABLE message_translations CHANGE COLUMN `value` `value` VARCHAR(400) DEFAULT NULL;');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE messages CHANGE COLUMN `default_value` `default_value` VARCHAR(255) NOT NULL;');
        $this->addSql('ALTER TABLE message_translations CHANGE COLUMN `value` `value` VARCHAR(255) DEFAULT NULL;');
    }
}
