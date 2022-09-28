<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220428123714 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $messages = [
        'USER.ACCOUNT.DELETE.TITLE' => [
            'default' => 'Are you sure you want to delete your account?',
            'locales' => [
                'en_GB' => 'Are you sure you want to delete your account?',
                'de_DE' => 'Möchten Sie Ihr Konto wirklich löschen??',
            ],
        ],

        'USER.ACCOUNT.BUTTON.CANCEL' => [
            'default' => 'Cancel',
            'locales' => [
                'en_GB' => 'Cancel',
                'de_DE' => 'Abbrechen',
            ],
        ],

        'USER.ACCOUNT.BUTTON.DELETE' => [
            'default' => 'Delete Account',
            'locales' => [
                'en_GB' => 'Delete Account',
                'de_DE' => 'Konto löschen',
            ],
        ],

        'DEALER_REQUEST.UNKNOWN_USER' => [
            'default' => 'Unknown user',
            'locales' => [
                'en_GB' => 'Unknown user',
                'de_DE' => 'Unbekannter Benutzer',
            ],
        ],
    ];

    public function up(Schema $schema): void
    {
        $this->removeMessages($this->messages);
        $this->addMessages($this->messages);
    }

    public function down(Schema $schema): void
    {
        $this->removeMessages($this->messages);
    }
}
