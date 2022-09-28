<?php

declare(strict_types=1);

namespace DoctrineMigrations;

require_once __DIR__ . '/../MessagesMigrationTrait.php';

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211026125920 extends AbstractMigration
{

    use MessagesMigrationTrait;

    private array $messages = [

        'USER.ACCOUNT.TITLE' => [
            'default' => 'Account management',
            'locales' => [
                'en-GB' => 'Account management',
                'de-DE' => 'Kontoverwaltung',
            ],
        ],

        'USER.ACCOUNT.SUB_TITLE' => [
            'default' => 'To change account data, fill out the form',
            'locales' => [
                'en-GB' => 'To change account data, fill out the form',
                'de-DE' => 'Um Kontodaten zu ändern, füllen Sie das Formular aus',
            ],
        ],

        'USER.ACCOUNT.DONE' => [
            'default' => 'Account details updated',
            'locales' => [
                'en-GB' => 'Account details updated',
                'de-DE' => 'Kontodaten aktualisiert',
            ],
        ],

        'USER.ACCOUNT.MESSAGE.SUCCESS' => [
            'default' => 'Account details were successfully updated',
            'locales' => [
                'en-GB' => 'Account details were successfully updated',
                'de-DE' => 'Kontodaten wurden erfolgreich aktualisiert',
            ],
        ],

        'USER.ACCOUNT.ACCEPT_TITLE' => [
            'default' => 'Subscriptions',
            'locales' => [
                'en-GB' => 'Subscriptions',
                'de-DE' => 'Abonnements',
            ],
        ],

        'USER.ACCOUNT.BUTTON.SUBMIT' => [
            'default' => 'Update Account',
            'locales' => [
                'en-GB' => 'Update Account',
                'de-DE' => 'Konto aktualisieren',
            ],
        ],

    ];

    public function up(Schema $schema): void
    {
        $this->addMessages($this->messages);
    }

    public function down(Schema $schema): void
    {
        $this->removeMessages($this->messages);
    }
}
