<?php

declare(strict_types=1);

namespace DoctrineMigrations;

require_once __DIR__ . '/../MessagesMigrationTrait.php';

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211025111559 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $messages = [
        'NAVIGATION.SMART_HOME_PLANNER' => [
            'default' => 'Smart Home Planner',
            'locales' => [
                'de-DE' => 'Smart Home Planner',
                'en-GB' => 'Smart Home Planner',
            ],
        ],
        'NAVIGATION.WISH_LIST' => [
            'default' => 'Wish List',
            'locales' => [
                'de-DE' => 'Merkzettel',
                'en-GB' => 'Wish List',
            ],
        ],
        'NAVIGATION.MY_ACCOUNT' => [
            'default' => 'My Account',
            'locales' => [
                'de-DE' => 'Mein Konto',
                'en-GB' => 'My Account',
            ],
        ],
        'NAVIGATION.SHARE' => [
            'default' => 'Share',
            'locales' => [
                'de-DE' => 'Teilen',
                'en-GB' => 'Share',
            ],
        ],
        'NAVIGATION.SAVE_CONFIGURATION' => [
            'default' => 'Save Configuration',
            'locales' => [
                'de-DE' => 'Konfiguration speichern',
                'en-GB' => 'Save Configuration',
            ],
        ],
        'SIDEBAR.SAMPLE_CONFIGURATIONS' => [
            'default' => 'Sample Configurations',
            'locales' => [
                'de-DE' => 'Anwendungsbeispiele',
                'en-GB' => 'Sample Configurations',
            ],
        ],
        'SIDEBAR.FILTERS' => [
            'default' => 'Filters',
            'locales' => [
                'de-DE' => 'Filtern',
                'en-GB' => 'Filters',
            ],
        ],
        'FILTER.CHOICE_ALL' => [
            'default' => 'All',
            'locales' => [
                'de-DE' => 'Alle',
                'en-GB' => 'All',
            ],
        ],
        'FILTER.RESET' => [
            'default' => 'Reset Filter',
            'locales' => [
                'de-DE' => 'Filter zurücksetzen',
                'en-GB' => 'Reset Filter',
            ],
        ],
        'SIDEBAR.PRODUCTS' => [
            'default' => 'Products',
            'locales' => [
                'de-DE' => 'Produkte',
                'en-GB' => 'Products',
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
