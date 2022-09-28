<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211023100117 extends AbstractMigration
{
    private array $generalSettings = [
        [
            // social
            'social_facebook' => 'http://example.com/facebook',
            'social_youtube'  => 'http://example.com/youtube',
        ],
    ];

    private array $registerMailTemplates = [
        'de-DE' => [
            'subject' => 'E-Mail-Betreff registrieren',
            'content' => 'Hallo.<br />
<br />
Bitte klicken Sie auf diesen Link, um Ihre Konfiguration aufzurufen:<br />
<a href="%%wish_list_link%%" style="color: #fcac22;">%%wish_list_link%%</a><br />
<br />
<br />
<br />
M&ouml;chten Sie Ihre Konfiguration l&auml;nger speichern, dann registrieren Sie sich <a href="%%confirm_register_link%%" style="color: #fcac22;">hier</a>.<br />
<br />
Vielen Dank.<br />
Ihr Somfy Privatkunden - Service!',
        ],
        'en-GB' => [
            'subject' => 'Register Mail Subject',
            'content' => 'Hello.<br />
<br />
Please click on this link to open your configuration:<br />
<a href="%%wish_list_link%%" style="color: #fcac22;">%%wish_list_link%%</a><br />
<br />
<br />
<br />
If you would like to save your configuration for a longer period, <a href="%%confirm_register_link%%" style="color: #fcac22;">register here</a>.<br />
<br />
Thanks very much.<br />
Your Somfy private customer service!',
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

    private function handleGeneralSettings(): void
    {
        // add settings
        foreach ($this->generalSettings as $settings) {
            $sql = $this->createInsertSQL('settings_general', $settings);
            $this->addSql($sql, $settings);
        }
    }

    private function handleRegisterMailTemplates(): void
    {
        foreach ($this->registerMailTemplates as $locale => $messages) {
            $this->addSql(
                "
                UPDATE `mail_template_translations` 
                SET
                    `subject` = :subject,
                    `content` = :content
                WHERE locale = :locale AND `type` = :type
            ",
                [
                    'subject' => $messages['subject'],
                    'content' => $messages['content'],
                    'locale'  => $locale,
                    'type'    => 'register',
                ]
            );
        }
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(
            'CREATE TABLE settings_general (id INT UNSIGNED AUTO_INCREMENT NOT NULL, social_facebook VARCHAR(255) DEFAULT NULL, social_youtube VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(
            'ALTER TABLE users ADD accept_privacy_policy TINYINT(1) DEFAULT \'0\' NOT NULL, ADD register_confirm_token BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', ADD register_confirm_valid_at DATETIME DEFAULT NULL, CHANGE locale locale VARCHAR(5) DEFAULT \'de-DE\' NOT NULL'
        );

        $this->handleGeneralSettings();
        $this->handleRegisterMailTemplates();
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE settings_general');
        $this->addSql(
            'ALTER TABLE users DROP accept_privacy_policy, DROP register_confirm_token, DROP register_confirm_valid_at, CHANGE locale locale VARCHAR(5) CHARACTER SET utf8mb4 DEFAULT \'de\' NOT NULL COLLATE `utf8mb4_unicode_ci`'
        );
    }
}
