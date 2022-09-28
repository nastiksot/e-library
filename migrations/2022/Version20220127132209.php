<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220127132209 extends AbstractMigration
{

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX idx_user_uid ON wish_lists');
        $this->addSql('ALTER TABLE wish_lists CHANGE user_uid uid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('CREATE INDEX idx_uid ON wish_lists (uid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX idx_uid ON wish_lists');
        $this->addSql('ALTER TABLE wish_lists CHANGE uid user_uid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('CREATE INDEX idx_user_uid ON wish_lists (user_uid)');
    }
}
