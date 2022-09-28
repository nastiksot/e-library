<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220125094151 extends AbstractMigration
{

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE wish_lists ADD user_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE wish_lists ADD CONSTRAINT FK_AE858703A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX idx_user_id ON wish_lists (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE wish_lists DROP FOREIGN KEY FK_AE858703A76ED395');
        $this->addSql('DROP INDEX idx_user_id ON wish_lists');
        $this->addSql('ALTER TABLE wish_lists DROP user_id');
    }
}
