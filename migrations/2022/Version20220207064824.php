<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220207064824 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `countries` AUTO_INCREMENT = 1;');
        $this->addSql("
            INSERT INTO `countries` (`code`, `created_at`, `updated_at`)
                VALUE('de', NOW(), NOW());
        ");
        $this->addSql("
            INSERT INTO `country_translations` (`translatable_id`, `locale`, `title`, `created_at`, `updated_at`)
                VALUE (1, 'de_DE', 'Deutschland', NOW(), NOW());
        ");
        $this->addSql("
            INSERT INTO `country_translations` (`translatable_id`, `locale`, `title`, `created_at`, `updated_at`)
            VALUE (1, 'en_GB', 'Germany', NOW(), NOW());
        ");

        $this->addSql("INSERT INTO `countries` (`code`, `created_at`, `updated_at`) VALUE('gb', NOW(), NOW());");
        $this->addSql("
            INSERT INTO `country_translations` (`translatable_id`, `locale`, `title`, `created_at`, `updated_at`)
                VALUE (2, 'de_DE', 'Großbritannien', NOW(), NOW());
        ");
        $this->addSql("
            INSERT INTO `country_translations` (`translatable_id`, `locale`, `title`, `created_at`, `updated_at`)
                VALUE (2, 'en_GB', 'Great Britain', NOW(), NOW());
        ");

        $this->addSql('ALTER TABLE `languages` AUTO_INCREMENT = 1;');
        $this->addSql("INSERT INTO `languages` (`code`, `created_at`, `updated_at`) VALUE('de', NOW(), NOW());");
        $this->addSql("
            INSERT INTO `language_translations` (`translatable_id`, `locale`, `title`, `created_at`, `updated_at`)
                VALUE (1, 'de_DE', 'Deutsch', NOW(), NOW());
        ");
        $this->addSql("
            INSERT INTO `language_translations` (`translatable_id`, `locale`, `title`, `created_at`, `updated_at`)
                VALUE (1, 'en_GB', 'German', NOW(), NOW());
        ");

        $this->addSql("INSERT INTO `languages` (`code`, `created_at`, `updated_at`) VALUE('en', NOW(), NOW());");
        $this->addSql("
            INSERT INTO `language_translations` (`translatable_id`, `locale`, `title`, `created_at`, `updated_at`)
                VALUE (2, 'de_DE', 'Englisch', NOW(), NOW());
        ");
        $this->addSql("
            INSERT INTO `language_translations` (`translatable_id`, `locale`, `title`, `created_at`, `updated_at`)
            VALUE (2, 'en_GB', 'English', NOW(), NOW());
        ");

        $this->addSql('INSERT INTO `country_languages` (`country_id`, `language_id`) VALUE(1, 1);');
        $this->addSql('INSERT INTO `country_languages` (`country_id`, `language_id`) VALUE(2, 2);');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM `countries`;');
        $this->addSql('DELETE FROM `languages`;');
    }
}
