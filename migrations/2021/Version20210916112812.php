<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210916112812 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `users` DROP `salt`');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `users` ADD `salt` VARCHAR(255) CHARACTER SET `utf8mb4` NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
