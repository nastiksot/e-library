<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220208160809 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE dealers (id INT UNSIGNED AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, country_name VARCHAR(255) DEFAULT NULL, region_name VARCHAR(255) DEFAULT NULL, city_name VARCHAR(255) DEFAULT NULL, address_line1 VARCHAR(255) DEFAULT NULL, address_line2 VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT 1 NOT NULL, image VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX idx_active (active), INDEX idx_created_at (created_at), INDEX idx_updated_at (updated_at), UNIQUE INDEX uniq_slug (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE users ADD dealer_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9249E6EA1 FOREIGN KEY (dealer_id) REFERENCES dealers (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_1483A5E9249E6EA1 ON users (dealer_id)');

        $this->addSql('DROP INDEX uniq_username ON users');
        $this->addSql('ALTER TABLE users DROP username');


        $this->addSql("UPDATE `mail_template_translations` SET `subject` = REPLACE(`subject`,'%%username%%,<br />','');");
        $this->addSql("UPDATE `mail_template_translations` SET `subject` = REPLACE(`subject`,'%%username%%,','');");
        $this->addSql("UPDATE `mail_template_translations` SET `subject` = REPLACE(`subject`,'%%username%%<br />','');");
        $this->addSql("UPDATE `mail_template_translations` SET `subject` = REPLACE(`subject`,'%%username%%','');");

        $this->addSql("UPDATE `mail_template_translations` SET `content` = REPLACE(`content`,'%%username%%,<br />','');");
        $this->addSql("UPDATE `mail_template_translations` SET `content` = REPLACE(`content`,'%%username%%,','');");
        $this->addSql("UPDATE `mail_template_translations` SET `content` = REPLACE(`content`,'%%username%%<br />','');");
        $this->addSql("UPDATE `mail_template_translations` SET `content` = REPLACE(`content`,'%%username%%','');");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9249E6EA1');
        $this->addSql('DROP INDEX IDX_1483A5E9249E6EA1 ON users');
        $this->addSql('ALTER TABLE users DROP dealer_id');

        $this->addSql('DROP TABLE dealers');

        $this->addSql('ALTER TABLE users ADD username VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX uniq_username ON users (username)');
    }
}
