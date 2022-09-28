<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220128161423 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $messages = [
        'NAVIGATION.TAB.HOUSE_VIEW' => [
            'default' => 'House View',
            'locales' => [
                'en-GB' => 'House View',
                'de-DE' => 'Hausansicht',
            ],
        ],

        'NAVIGATION.TAB.PRODUCT_SETS_VIEW' => [
            'default' => 'List View',
            'locales' => [
                'en-GB' => 'List View',
                'de-DE' => 'Listenansicht',
            ],
        ],

        'NAVIGATION.TAB.PLAY_VIDEO' => [
            'default' => 'Play Video',
            'locales' => [
                'en-GB' => 'Play Video',
                'de-DE' => 'Video abspielen',
            ],
        ],

        'NAVIGATION.TAB.VIDEO' => [
            'default' => 'Video',
            'locales' => [
                'en-GB' => 'Video',
                'de-DE' => 'Video',
            ],
        ],

        'NAVIGATION.TAB.PRODUCT_SET_PRODUCTS_VIEW' => [
            'default' => 'List View',
            'locales' => [
                'en-GB' => 'List View',
                'de-DE' => 'Listenansicht',
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
