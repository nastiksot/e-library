<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210816161202 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql("
            INSERT INTO `menu` (`id`, `type`, `link_page_id`, `position`, `active`, `link_type`, `link`, `link_target`, `no_follow`, `css_class`, `created_at`, `updated_at`) VALUES
                (7, 'bottom', NULL, 0, 1, 'link', '#', '_top', 0, 'button', NULL, NULL),
                (8, 'bottom', NULL, 1, 1, 'link', '#', '_top', 0, 'hollow', NULL, NULL),
                (9, 'bottom', NULL, 2, 1, 'link', '#', '_top', 0, 'hollow', NULL, NULL),
                (10, 'bottom', NULL, 3, 1, 'link', '#', '_top', 0, 'text', NULL, NULL);
        ");

        $this->addSql("
            INSERT INTO `menu_translations` (`id`, `translatable_id`, `title`, `created_at`, `updated_at`, `locale`, `type`) VALUES
                (13, 7, 'Somfy Hilfe-Center', NULL, NULL, 'de-DE', 'bottom'),
                (14, 7, 'Somfy Help Center', NULL, NULL, 'en-GB', 'bottom'),
                (15, 8, 'Onlineshop', NULL, NULL, 'de-DE', 'bottom'),
                (16, 8, 'Online Shop', NULL, NULL, 'en-GB', 'bottom'),
                (17, 9, 'Händlersuche', NULL, NULL, 'de-DE', 'bottom'),
                (18, 9, 'Store Locator', NULL, NULL, 'en-GB', 'bottom'),
                (19, 10, 'Abonnieren Sie hier unseren Newsletter', NULL, NULL, 'de-DE', 'bottom'),
                (20, 10, 'Subscribe to Our Newsletter', NULL, NULL, 'en-GB', 'bottom');
        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM `menu` WHERE id >= 7 AND id <= 10');
    }
}
