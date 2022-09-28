<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220202171344 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

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
    ];

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE country_languages (country_id INT UNSIGNED NOT NULL, language_id INT UNSIGNED NOT NULL, INDEX IDX_15325619F92F3E70 (country_id), INDEX IDX_1532561982F1BAF4 (language_id), PRIMARY KEY(country_id, language_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE language_translations (id INT UNSIGNED AUTO_INCREMENT NOT NULL, translatable_id INT UNSIGNED DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_E3BB4E522C2AC5D3 (translatable_id), UNIQUE INDEX language_translations_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE languages (id INT UNSIGNED AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX idx_code (code), INDEX idx_active (active), INDEX idx_created_at (created_at), INDEX idx_updated_at (updated_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE country_languages ADD CONSTRAINT FK_15325619F92F3E70 FOREIGN KEY (country_id) REFERENCES countries (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE country_languages ADD CONSTRAINT FK_1532561982F1BAF4 FOREIGN KEY (language_id) REFERENCES languages (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE language_translations ADD CONSTRAINT FK_E3BB4E522C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES languages (id) ON DELETE CASCADE');


        foreach ($this->translationTables as $tableName) {
            $this->addSql("UPDATE `{$tableName}` SET `locale` = REPLACE(`locale`,'-','_');");
        }
    }

    public function down(Schema $schema): void
    {
        foreach ($this->translationTables as $tableName) {
            $this->addSql("UPDATE `{$tableName}` SET `locale` = REPLACE(`locale`,'_','-');");
        }

        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE country_languages DROP FOREIGN KEY FK_1532561982F1BAF4');
        $this->addSql('ALTER TABLE language_translations DROP FOREIGN KEY FK_E3BB4E522C2AC5D3');
        $this->addSql('DROP TABLE country_languages');
        $this->addSql('DROP TABLE language_translations');
        $this->addSql('DROP TABLE languages');
    }
}
