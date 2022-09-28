<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220208171600 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `wish_lists` DROP INDEX `idx_uid`, ADD UNIQUE INDEX `uniq_uid` (`uid`)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `wish_lists` DROP INDEX `uniq_uid`, ADD INDEX `idx_uid` (`uid`)');
    }
}
