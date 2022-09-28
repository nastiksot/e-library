<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220321172103 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('UPDATE `wish_lists` SET `name`=`updated_at`');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('UPDATE `wish_lists` SET `name`=NULL');
    }
}
