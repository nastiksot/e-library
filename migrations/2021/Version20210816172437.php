<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210816172437 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE messages (
                id INT UNSIGNED AUTO_INCREMENT NOT NULL,
                code VARCHAR(255) NOT NULL,
                default_value VARCHAR(255) NOT NULL,

                UNIQUE INDEX uniq_code (code),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        $this->addSql('
            CREATE TABLE message_translations (
                id INT UNSIGNED AUTO_INCREMENT NOT NULL,
                translatable_id INT UNSIGNED DEFAULT NULL,
                locale VARCHAR(5) NOT NULL,

                value VARCHAR(255) DEFAULT NULL,

                created_at DATETIME DEFAULT NULL,
                updated_at DATETIME DEFAULT NULL,

                INDEX IDX_2B6EF612C2AC5D3 (translatable_id),
                UNIQUE INDEX message_translations_unique_translation (translatable_id, locale),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        $this->addSql('ALTER TABLE message_translations ADD CONSTRAINT FK_2B6EF612C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES messages (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE message_translations DROP FOREIGN KEY FK_2B6EF612C2AC5D3');
        $this->addSql('DROP TABLE message_translations');
        $this->addSql('DROP TABLE messages');
    }
}
