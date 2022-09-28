<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211109113513 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `page_translations` ADD `logo_title` VARCHAR(255) DEFAULT NULL, ADD `og_image` VARCHAR(1000) DEFAULT NULL, ADD `additional_meta_tags` LONGTEXT DEFAULT NULL');
        $this->addSql("ALTER TABLE `pages` ADD `code_block_active` TINYINT(1) DEFAULT '0' NOT NULL, ADD `code_block` LONGTEXT DEFAULT NULL, DROP `og_image`");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `page_translations` DROP `logo_title`, DROP `og_image`, DROP `additional_meta_tags`');
        $this->addSql('ALTER TABLE `pages` ADD `og_image` VARCHAR(1000) CHARACTER SET `utf8mb4` DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP `code_block_active`, DROP `code_block`');
    }
}
