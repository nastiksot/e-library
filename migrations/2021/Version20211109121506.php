<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211109121506 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `decision_translations` ADD `answer` VARCHAR(255) DEFAULT NULL, CHANGE `title` `question` VARCHAR(255) DEFAULT NULL');
        $this->addSql('UPDATE `decision_translations` SET `answer` = `question`');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `decision_translations` ADD `title` VARCHAR(255) CHARACTER SET `utf8mb4` DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('UPDATE `decision_translations` SET `title` = `question`');
        $this->addSql('UPDATE `decision_translations` SET `title` = `question`');
        $this->addSql('ALTER TABLE `decision_translations` DROP `question`, DROP `answer`');
    }
}
