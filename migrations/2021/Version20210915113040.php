<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210915113040 extends AbstractMigration
{

    private array $mailTemplates = [
        [
            'type'      => 'register',
            'send_from' => 'from@localhost',
        ],
        [
            'type'      => 'register-notification',
            'send_to'   => 'to@localhost,to2@localhost',
            'send_from' => 'from@localhost',
        ],
        [
            'type'      => 'forgot-password',
            'send_from' => 'from@localhost',
        ],
    ];

    private function createInsertSQL(
        string $table,
        array &$data,
        ?bool $isCreatedAt = false,
        ?bool $isUpdatedAt = false
    ): string {
        if ($isCreatedAt) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        if ($isUpdatedAt) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }

        return 'INSERT INTO `' . $table . '`
        (`' . implode('`, `', array_keys($data)) . '`)
        VALUES
        (:' . implode(', :', array_keys($data)) . ')';
    }


    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mail_logs (id VARCHAR(190) NOT NULL, status VARCHAR(15) NOT NULL, debug LONGTEXT DEFAULT NULL, subject VARCHAR(1000) DEFAULT NULL, message LONGTEXT DEFAULT NULL, `from` VARCHAR(255) DEFAULT NULL, reply_to VARCHAR(255) DEFAULT NULL, `to` VARCHAR(255) DEFAULT NULL, cc VARCHAR(255) DEFAULT NULL, bcc VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, INDEX idx_created_at (created_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mail_template_translations (id INT UNSIGNED AUTO_INCREMENT NOT NULL, translatable_id INT UNSIGNED DEFAULT NULL, subject VARCHAR(255) DEFAULT NULL, content LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, locale VARCHAR(5) NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_2C67A9AA2C2AC5D3 (translatable_id), INDEX idx_created_at (created_at), INDEX idx_updated_at (updated_at), UNIQUE INDEX mail_template_translations_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mail_templates (id INT UNSIGNED AUTO_INCREMENT NOT NULL, send_to VARCHAR(255) DEFAULT NULL, send_from VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, type VARCHAR(255) NOT NULL, INDEX idx_active (active), INDEX idx_created_at (created_at), INDEX idx_updated_at (updated_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mail_template_translations ADD CONSTRAINT FK_2C67A9AA2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES mail_templates (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users ADD first_name VARCHAR(255) DEFAULT NULL, ADD last_name VARCHAR(255) DEFAULT NULL, ADD accept_news TINYINT(1) DEFAULT \'0\' NOT NULL, ADD accept_process_personal_data TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('CREATE INDEX idx_accept_news ON users (accept_news)');
        $this->addSql('CREATE INDEX idx_accept_process_personal_data ON users (accept_process_personal_data)');

        // add templates
        foreach ($this->mailTemplates as $template) {
            $sql = $this->createInsertSQL('mail_templates', $template, true, true);
            $this->addSql($sql, $template);
        }

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mail_template_translations DROP FOREIGN KEY FK_2C67A9AA2C2AC5D3');
        $this->addSql('DROP TABLE mail_logs');
        $this->addSql('DROP TABLE mail_template_translations');
        $this->addSql('DROP TABLE mail_templates');
        $this->addSql('DROP INDEX idx_accept_news ON users');
        $this->addSql('DROP INDEX idx_accept_process_personal_data ON users');
        $this->addSql('ALTER TABLE users DROP first_name, DROP last_name, DROP accept_news, DROP accept_process_personal_data');
    }
}
