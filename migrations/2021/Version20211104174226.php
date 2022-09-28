<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211104174226 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql("ALTER TABLE `decisions` ADD `active` TINYINT(1) DEFAULT '1' NOT NULL");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `decisions` DROP `active`');
    }
}
