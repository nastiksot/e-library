<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220127192113 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $messages = [

        'USER.WISHLISTS.LABEL.CREATED_AT' => [
            'default' => 'Created At',
            'locales' => [
                'en-GB' => 'Created At',
                'de-DE' => 'Hergestellt in',
            ],
        ],

        'USER.WISHLISTS.LABEL.UPDATED_AT' => [
            'default' => 'Updated At',
            'locales' => [
                'en-GB' => 'Updated At',
                'de-DE' => 'Aktualisiert am',
            ],
        ],

        'USER.WISHLISTS.ACTIONS' => [
            'default' => 'Actions',
            'locales' => [
                'en-GB' => 'Actions',
                'de-DE' => 'Aktionen',
            ],
        ],

        'USER.WISHLISTS.NO_DATA' => [
            'default' => 'No data available',
            'locales' => [
                'en-GB' => 'No data available',
                'de-DE' => 'Keine Daten verfügbar',
            ],
        ],

        'USER.WISHLISTS.DELETE.TITLE' => [
            'default' => 'Are you sure you want to delete this WishList?',
            'locales' => [
                'en-GB' => 'Are you sure you want to delete this WishList?',
                'de-DE' => 'Möchten Sie diese WishList wirklich löschen?',
            ],
        ],

        'USER.WISHLISTS.BUTTON.CANCEL' => [
            'default' => 'Cancel',
            'locales' => [
                'en-GB' => 'Cancel',
                'de-DE' => 'Abbrechen',
            ],
        ],

        'USER.WISHLISTS.BUTTON.DELETE' => [
            'default' => 'Delete',
            'locales' => [
                'en-GB' => 'Delete',
                'de-DE' => 'Löschen',
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
