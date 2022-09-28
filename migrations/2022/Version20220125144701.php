<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220125144701 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $messages = [

        'WISHLIST.EMPTY.REGISTER.LINK_TITLE' => [
            'default' => 'Create profile or Sign in',
            'locales' => [
                'en-GB' => 'Create profile or Sign in',
                'de-DE' => 'Profil erstellen oder Anmelden',
            ],
        ],

        'WISHLIST.SHARE.TITLE' => [
            'default' => 'Link to Share:',
            'locales' => [
                'en-GB' => 'Link to Share:',
                'de-DE' => 'Link zum Teilen:',
            ],
        ],

        'WISHLIST.SHARE.COPY' => [
            'default' => 'Copy Link',
            'locales' => [
                'en-GB' => 'Copy Link',
                'de-DE' => 'Link Kopieren',
            ],
        ],

        'WISHLIST.SHARE.SOCIAL' => [
            'default' => 'Or Share Via:',
            'locales' => [
                'en-GB' => 'Or Share Via:',
                'de-DE' => 'Oder Teilen per:',
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
