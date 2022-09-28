<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220124095745 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $messages = [
        'WISHLIST.EMPTY.TITLE' => [
            'default' => 'Your Wish List is empty!',
            'locales' => [
                'en-GB' => 'Your Wish List is empty!',
                'de-DE' => 'Dein Merkzettel ist leer!',
            ],
        ],
        'WISHLIST.EMPTY.DESCRIPTION' => [
            'default' => 'What are your favorite products or scenarios? Save and organize them here until you\'ve gathered everything for your purchase.',
            'locales' => [
                'en-GB' => 'What are your favorite products or scenarios? Save and organize them here until you\'ve gathered everything for your purchase.',
                'de-DE' => 'Was sind Ihre Lieblingsprodukte oder -szenarien? Speichern und organisieren Sie diese hier, bis Sie alles für Ihren Einkauf zusammengestellt haben.',
            ],
        ],

        'WISHLIST.EMPTY.REGISTER.TITLE' => [
            'default' => 'Keep your notepads!',
            'locales' => [
                'en-GB' => 'Keep your notepads!',
                'de-DE' => 'Behalte deine Merkzettel bei!',
            ],
        ],

        'WISHLIST.EMPTY.REGISTER.LINK_PREFIX' => [
            'default' => 'These notepads are only available temporarily.',
            'locales' => [
                'en-GB' => 'These notepads are only available temporarily.',
                'de-DE' => 'Diese Merkzettel sind nur vorübergehend verfügbar.',
            ],
        ],

        'WISHLIST.EMPTY.REGISTER.LINK_LABEL' => [
            'default' => 'Create profile or Sign in',
            'locales' => [
                'en-GB' => 'Create profile or Sign in',
                'de-DE' => 'Profil erstellen oder Anmelden',
            ],
        ],

        'WISHLIST.EMPTY.REGISTER.LINK_TITLE' => [
            'default' => 'Create profile or Sign in',
            'locales' => [
                'en-GB' => 'Create profile or Sign in',
                'de-DE' => 'Create profile or Sign in',
            ],
        ],

        'WISHLIST.EMPTY.REGISTER.LINK_SUFFIX' => [
            'default' => 'to make sure the notepads are still there when you come back.',
            'locales' => [
                'en-GB' => 'to make sure the notepads are still there when you come back.',
                'de-DE' => 'um sicherzustellen, dass die Merkzettel noch vorhanden sind, wenn du wieder zurückkommst.',
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
