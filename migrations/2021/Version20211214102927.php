<?php

declare(strict_types=1);

namespace DoctrineMigrations;

require_once __DIR__ . '/../SqlMigrationTrait.php';

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211214102927 extends AbstractMigration
{

    use SqlMigrationTrait;

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE partner_translations (id INT UNSIGNED AUTO_INCREMENT NOT NULL, translatable_id INT UNSIGNED DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, locale VARCHAR(5) NOT NULL, INDEX IDX_579AC5A62C2AC5D3 (translatable_id), UNIQUE INDEX partner_translations_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE partners (id INT UNSIGNED AUTO_INCREMENT NOT NULL, position INT DEFAULT 0 NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, image VARCHAR(255) DEFAULT NULL, INDEX idx_position (position), INDEX idx_active (active), INDEX idx_created_at (created_at), INDEX idx_updated_at (updated_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(
            'ALTER TABLE partner_translations ADD CONSTRAINT FK_579AC5A62C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES partners (id) ON DELETE CASCADE'
        );

//        for ($i = 1; $i <= 14; $i++) {
//            $data = ['position' => $i - 1];
//            $sql  = $this->createInsertSQL('partners', $data, true, true);
//            $this->addSql($sql, $data);
//        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE partner_translations DROP FOREIGN KEY FK_579AC5A62C2AC5D3');
        $this->addSql('DROP TABLE partner_translations');
        $this->addSql('DROP TABLE partners');
    }
}
