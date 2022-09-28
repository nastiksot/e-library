<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220217103431 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE dealer_requests (
                id INT UNSIGNED AUTO_INCREMENT NOT NULL,
                dealer_id INT UNSIGNED NOT NULL,
                wish_list_id INT UNSIGNED DEFAULT NULL,
                user_id INT UNSIGNED DEFAULT NULL,
                client_name VARCHAR(255) DEFAULT NULL,
                email VARCHAR(255) DEFAULT NULL,
                phone VARCHAR(255) DEFAULT NULL,
                address VARCHAR(255) DEFAULT NULL,
                status VARCHAR(255) DEFAULT \'new\' NOT NULL COMMENT \'(DC2Type:App\\\\Contracts\\\\Dictionary\\\\DealerRequestStatus)\',
                created_at DATETIME DEFAULT NULL,
                updated_at DATETIME DEFAULT NULL,
                message TEXT DEFAULT NULL,

                INDEX IDX_89A10831249E6EA1 (dealer_id),
                INDEX IDX_89A10831D69F3311 (wish_list_id),
                INDEX IDX_89A10831A76ED395 (user_id),
                INDEX idx_status (status),
                INDEX idx_created_at (created_at),
                INDEX idx_updated_at (updated_at),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
        $this->addSql('ALTER TABLE dealer_requests ADD CONSTRAINT FK_89A10831249E6EA1 FOREIGN KEY (dealer_id) REFERENCES dealers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dealer_requests ADD CONSTRAINT FK_89A10831D69F3311 FOREIGN KEY (wish_list_id) REFERENCES wish_lists (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE dealer_requests ADD CONSTRAINT FK_89A10831A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE dealer_requests');
    }
}
