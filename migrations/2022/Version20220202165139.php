<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220202165139 extends AbstractMigration
{

    use MessagesMigrationTrait;

    private array $messages = [

        'WISHLIST.SAVE.TITLE' => [
            'default' => 'Save configuration',
            'locales' => [
                'en-GB' => 'Save Wish List',
                'de-DE' => 'Merkzettel Speichern',
            ],
        ],

        'WISHLIST.SAVE.SUB_TITLE' => [
            'default' => 'Save Wish List configuration to your profile',
            'locales' => [
                'en-GB' => 'Save Wish List configuration to your profile',
                'de-DE' => 'Speichern Sie Merkzettel in Ihrem Profil',
            ],
        ],

        'WISHLIST.SAVE.BUTTON.SUBMIT' => [
            'default' => 'Save Wish List',
            'locales' => [
                'en-GB' => 'Save Wish List',
                'de-DE' => 'Merkzettel Speichern',
            ],
        ],

        'WISHLIST.SAVE.MESSAGE.SUCCESS' => [
            'default' => 'Wish List was successfully saved',
            'locales' => [
                'en-GB' => 'Wish List was successfully saved',
                'de-DE' => 'Merkzettel wurde erfolgreich gespeichert',
            ],
        ],

        'USER.ACCOUNT.HELLO' => [
            'default' => 'Hello',
            'locales' => [
                'en-GB' => 'Hello',
                'de-DE' => 'Hallo',
            ],
        ],

        'USER.ACCOUNT.WELCOME' => [
            'default' => 'Welcome to your Smart Home Planner account',
            'locales' => [
                'en-GB' => 'Welcome to your Smart Home Planner account',
                'de-DE' => 'Willkommen in Ihrem Smart Home Planer-Konto',
            ],
        ],


        'USER.ACCOUNT.MENU.USER_DATA' => [
            'default' => 'Account Management',
            'locales' => [
                'en-GB' => 'Account Management',
                'de-DE' => 'Kontoverwaltung',
            ],
        ],


        'USER.ACCOUNT.MENU.WISHLISTS' => [
            'default' => 'Saved Wish Lists',
            'locales' => [
                'en-GB' => 'Saved Wish Lists',
                'de-DE' => 'Gerettet Merkzettel',
            ],
        ],

        'USER.ACCOUNT.BUTTON.LOGOUT' => [
            'default' => 'Logout',
            'locales' => [
                'en-GB' => 'Logout',
                'de-DE' => 'Abmelden',
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
