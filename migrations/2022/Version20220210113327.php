<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220210113327 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $messages = [
        'USER.ACCOUNT.MENU.REQUESTS' => [
            'default' => 'Customer Requests',
            'locales' => [
                'en_GB' => 'Customer Requests',
                'de_DE' => 'Kundenanfragen',
            ],
        ],

        'USER.ACCOUNT.MENU.DEALER_LIST' => [
            'default' => 'Employee Management',
            'locales' => [
                'en_GB' => 'Employee Management',
                'de_DE' => 'Mitarbeiter verwalten',
            ],
        ],

        'DEALER_USER.LIST.ACTIONS' => [
            'default' => 'Actions',
            'locales' => [
                'en_GB' => 'Actions',
                'de_DE' => 'Aktionen',
            ],
        ],

        'DEALER_USER.LIST.DELETE.TITLE' => [
            'default' => 'Are you sure you want to delete this User?',
            'locales' => [
                'en_GB' => 'Are you sure you want to delete this User?',
                'de_DE' => 'Möchten Sie diesen Benutzer wirklich löschen?',
            ],
        ],

        'DEALER_USER.LIST.BUTTON.CANCEL' => [
            'default' => 'Cancel',
            'locales' => [
                'en_GB' => 'Cancel',
                'de_DE' => 'Abbrechen',
            ],
        ],

        'DEALER_USER.LIST.BUTTON.DELETE' => [
            'default' => 'Delete',
            'locales' => [
                'en_GB' => 'Delete',
                'de_DE' => 'Löschen',
            ],
        ],

        'DEALER_USER.LIST.BUTTON.CREATE' => [
            'default' => 'Create a new user',
            'locales' => [
                'en_GB' => 'Create a new user',
                'de_DE' => 'Erstellen Sie einen neuen Benutzer',
            ],
        ],

        'DEALER_USER.ACCOUNT.BUTTON.CREATE' => [
            'default' => 'Create',
            'locales' => [
                'en_GB' => 'Create',
                'de_DE' => 'Erstellen',
            ],
        ],

        'DEALER_USER.ACCOUNT.BUTTON.DELETE' => [
            'default' => 'Delete Account',
            'locales' => [
                'en_GB' => 'Delete Account',
                'de_DE' => 'Konto löschen',
            ],
        ],

        'DEALER_USER.ACCOUNT.BUTTON.UPDATE' => [
            'default' => 'Update Account',
            'locales' => [
                'en_GB' => 'Update Account',
                'de_DE' => 'Konto aktualisieren',
            ],
        ],

        'DEALER_USER.ACCOUNT.EDIT.TITLE' => [
            'default' => 'Employee Management',
            'locales' => [
                'en_GB' => 'Employee Management',
                'de_DE' => 'Mitarbeiter verwalten',
            ],
        ],

        'DEALER_USER.ACCOUNT.MESSAGE.SUCCESS' => [
            'default' => 'Account details were successfully saved',
            'locales' => [
                'en_GB' => 'Account details were successfully saved',
                'de_DE' => 'Kontodaten wurden erfolgreich gespeichert',
            ],
        ],

        'DEALER_USER.ACCOUNT.FIRST_NAME' => [
            'default' => 'First Name',
            'locales' => [
                'en_GB' => 'First Name',
                'de_DE' => 'Vorname',
            ],
        ],

        'DEALER_USER.ACCOUNT.LAST_NAME' => [
            'default' => 'Last Name',
            'locales' => [
                'en_GB' => 'Last Name',
                'de_DE' => 'Nachname',
            ],
        ],

        'DEALER_USER.ACCOUNT.EMAIL' => [
            'default' => 'Email',
            'locales' => [
                'en_GB' => 'Email',
                'de_DE' => 'Email',
            ],
        ],

        'DEALER_USER.ACCOUNT.PASSWORD' => [
            'default' => 'Password',
            'locales' => [
                'en_GB' => 'Password',
                'de_DE' => 'Passwort',
            ],
        ],

        'DEALER_USER.ACCOUNT.ACTIVE' => [
            'default' => 'Is Active',
            'locales' => [
                'en_GB' => 'Is Active',
                'de_DE' => 'Ist aktiv',
            ],
        ],

        'DEALER_USER.ACCOUNT.ROLE' => [
            'default' => 'Role',
            'locales' => [
                'en_GB' => 'Role',
                'de_DE' => 'Role',
            ],
        ],

        'DEALER_USER.ACCOUNT.ROLE.ROLE_DEALER_ADMIN' => [
            'default' => 'Admin',
            'locales' => [
                'en_GB' => 'Admin',
                'de_DE' => 'Admin',
            ],
        ],

        'DEALER_USER.ACCOUNT.ROLE.ROLE_DEALER_EMPLOYEE' => [
            'default' => 'Employee',
            'locales' => [
                'en_GB' => 'Employee',
                'de_DE' => 'Mitarbeiter',
            ],
        ],

        'DEALER_USER.ACCOUNT.ROLE.ERROR.INVALID' => [
            'default' => 'Invalid role',
            'locales' => [
                'en_GB' => 'Invalid role',
                'de_DE' => 'Ungültige Rolle',
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
