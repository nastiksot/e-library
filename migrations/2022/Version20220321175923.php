<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220321175923 extends AbstractMigration
{

    private array $translationTables = [
        'country_translations',
        'decision_translations',
        'filter_group_translations',
        'filter_translations',
        'language_translations',
        'mail_template_translations',
        'menu_translations',
        'message_translations',
        'page_translations',
        'partner_translations',
        'product_set_translations',
        'product_translations',
        'slider_translations',
        'users',
    ];

    public function up(Schema $schema): void
    {
        foreach ($this->translationTables as $tableName) {
            $this->addSql("UPDATE `{$tableName}` SET `locale` = REPLACE(`locale`,'-','_');");
        }

    }

    public function down(Schema $schema): void
    {
        foreach ($this->translationTables as $tableName) {
            $this->addSql("UPDATE `{$tableName}` SET `locale` = REPLACE(`locale`,'_','-');");
        }
    }
}
