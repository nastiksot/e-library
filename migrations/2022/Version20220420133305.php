<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220420133305 extends AbstractMigration
{

    public function up(Schema $schema): void
    {
        $this->addSql(
            'ALTER TABLE settings_general ADD social_instagram VARCHAR(255) NULL;'
        );

        $this->addSql(
            'UPDATE settings_general SET social_instagram = "https://www.instagram.com/somfy_dach/";'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(
            'ALTER TABLE settings_general DROP social_instagram;'
        );
    }
}
