<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220209144017 extends AbstractMigration
{

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users CHANGE locale locale VARCHAR(5) DEFAULT \'de_DE\' NOT NULL');

        $this->addSql("UPDATE `users` SET `locale` = REPLACE(`locale`,'-','_');");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users CHANGE locale locale VARCHAR(5) DEFAULT \'de-DE\' NOT NULL');

        $this->addSql("UPDATE `users` SET `locale` = REPLACE(`locale`,'_','-');");
    }
}
