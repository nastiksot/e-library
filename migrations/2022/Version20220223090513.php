<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220223090513 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql(
            '
            ALTER TABLE dealer_requests
                ADD copy_sent_at DATETIME DEFAULT NULL,
                ADD send_copy TINYINT(1) DEFAULT 0 NOT NULL,
                ADD locale VARCHAR(5) DEFAULT NULL
                '
        );
        $this->addSql('CREATE INDEX idx_send_copy ON dealer_requests (send_copy)');
        $this->addSql('CREATE INDEX idx_copy_sent_at ON dealer_requests (copy_sent_at)');
        $this->addSql('ALTER TABLE dealers ADD email VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mail_template_translations ADD content2 LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX idx_send_copy ON dealer_requests');
        $this->addSql('DROP INDEX idx_copy_sent_at ON dealer_requests');
        $this->addSql(
            '
            ALTER TABLE dealer_requests
                DROP copy_sent_at,
                DROP send_copy,
                DROP locale
                '
        );
        $this->addSql('ALTER TABLE dealers DROP email');
        $this->addSql('ALTER TABLE mail_template_translations DROP content2');
    }
}
