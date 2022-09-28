<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220217175045 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE dealer_requests CHANGE client_name contact_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE dealer_requests RENAME INDEX idx_89a10831249e6ea1 TO idx_dealer_id');
        $this->addSql('ALTER TABLE dealer_requests RENAME INDEX idx_89a10831d69f3311 TO idx_wish_list_id');
        $this->addSql('ALTER TABLE dealer_requests RENAME INDEX idx_89a10831a76ed395 TO idx_user_id');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE dealer_requests CHANGE contact_name client_name  VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE dealer_requests RENAME INDEX idx_dealer_id TO IDX_89A10831249E6EA1');
        $this->addSql('ALTER TABLE dealer_requests RENAME INDEX idx_user_id TO IDX_89A10831A76ED395');
        $this->addSql('ALTER TABLE dealer_requests RENAME INDEX idx_wish_list_id TO IDX_89A10831D69F3311');
    }
}
