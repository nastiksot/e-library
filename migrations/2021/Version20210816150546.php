<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210816150546 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql("
            INSERT INTO `pages` (`id`, `og_image`, `slug`, `redirect`, `created_at`, `updated_at`, `type`) VALUES
                (1, NULL, 'impressum', NULL, NULL, NULL, 'system'),
                (2, NULL, 'guarantee-conditions', NULL, NULL, NULL, 'system'),
                (3, NULL, 'cookie-policy', NULL, NULL, NULL, 'system'),
                (4, NULL, 'data-protection', NULL, NULL, NULL, 'system'),
                (5, NULL, 'terms-and-conditions', NULL, NULL, NULL, 'system'),
                (6, NULL, 'sitemap', NULL, NULL, NULL, 'system');
        ");

        $this->addSql("
            INSERT INTO `page_translations` (`id`, `translatable_id`, `meta_title`, `meta_keywords`, `meta_description`, `og_title`, `og_description`, `title`, `content`, `created_at`, `updated_at`, `locale`, `type`) VALUES
                (1, 1, 'Impressum', NULL, NULL, NULL, NULL, 'Impressum', 'Impressum', NULL, NULL, 'de-DE', 'system'),
                (2, 1, NULL, NULL, NULL, NULL, NULL, 'Impressum', 'Impressum', NULL, NULL, 'en-GB', 'system'),
                (3, 2, NULL, NULL, NULL, NULL, NULL, 'Garantiebedingungen', 'Garantiebedingungen', NULL, NULL, 'de-DE', 'system'),
                (4, 2, NULL, NULL, NULL, NULL, NULL, 'Guarantee conditions', 'Guarantee conditions', NULL, NULL, 'en-GB', 'system'),
                (5, 3, NULL, NULL, NULL, NULL, NULL, 'Cookie-Richtlinie', '<p>Cookie-Richtlinie</p>', NULL, NULL, 'de-DE', 'system'),
                (6, 3, NULL, NULL, NULL, NULL, NULL, 'Cookie Policy', 'Cookie Policy', NULL, NULL, 'en-GB', 'system'),
                (7, 4, NULL, NULL, NULL, NULL, NULL, 'Datenschutzerklärung', 'Datenschutzerkl&auml;rung', NULL, NULL, 'de-DE', 'system'),
                (8, 4, NULL, NULL, NULL, NULL, NULL, 'Data protection', 'Data protection', NULL, NULL, 'en-GB', 'system'),
                (9, 5, NULL, NULL, NULL, NULL, NULL, 'AGB', '<p>AGB</p>', NULL, NULL, 'de-DE', 'system'),
                (10, 5, NULL, NULL, NULL, NULL, NULL, 'Terms and Conditions', 'Terms and Conditions', NULL, NULL, 'en-GB', 'system'),
                (11, 6, NULL, NULL, NULL, NULL, NULL, 'Sitemap', 'Sitemap', NULL, NULL, 'de-DE', 'system'),
                (12, 6, NULL, NULL, NULL, NULL, NULL, 'Sitemap', 'Sitemap', NULL, NULL, 'en-GB', 'system');
        ");


        $this->addSql("
            INSERT INTO `menu` (`id`, `link_page_id`, `css_class`, `active`, `position`, `link_type`, `link`, `link_target`, `no_follow`, `created_at`, `updated_at`, `type`) VALUES
                (1, 1, NULL, 1, 0, 'page', NULL, '_top', 0, NULL, NULL, 'footer'),
                (2, 2, NULL, 1, 1, 'page', NULL, '_top', 0, NULL, NULL, 'footer'),
                (3, 3, NULL, 1, 2, 'page', NULL, '_top', 0, NULL, NULL, 'footer'),
                (4, 4, NULL, 1, 3, 'page', NULL, '_top', 0, NULL, NULL, 'footer'),
                (5, 5, NULL, 1, 4, 'page', NULL, '_top', 0, NULL, NULL, 'footer'),
                (6, 6, NULL, 1, 5, 'page', NULL, '_top', 0, NULL, NULL, 'footer');
        ");

        $this->addSql("
            INSERT INTO `menu_translations` (`id`, `translatable_id`, `title`, `created_at`, `updated_at`, `locale`, `type`) VALUES
                (1, 1, 'Impressum', NULL, NULL, 'de-DE', 'footer'),
                (2, 1, 'Impressum', NULL, NULL, 'en-GB', 'footer'),
                (3, 2, 'Garantiebedingungen', NULL, NULL, 'de-DE', 'footer'),
                (4, 2, 'Guarantee conditions', NULL, NULL, 'en-GB', 'footer'),
                (5, 3, 'Cookie-Richtlinie', NULL, NULL, 'de-DE', 'footer'),
                (6, 3, 'Cookie Policy', NULL, NULL, 'en-GB', 'footer'),
                (7, 4, 'Datenschutzerklärung', NULL, NULL, 'de-DE', 'footer'),
                (8, 4, 'Data Protection', NULL, NULL, 'en-GB', 'footer'),
                (9, 5, 'AGB', NULL, NULL, 'de-DE', 'footer'),
                (10, 5, 'Terms and Conditions', NULL, NULL, 'en-GB', 'footer'),
                (11, 6, 'Sitemap', NULL, NULL, 'de-DE', 'footer'),
                (12, 6, 'Sitemap', NULL, NULL, 'en-GB', 'footer');
        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM `menu`');
        $this->addSql('DELETE FROM `pages`');
    }
}
