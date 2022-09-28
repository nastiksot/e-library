<?php

declare(strict_types=1);

namespace DoctrineMigrations;

require_once __DIR__ . '/../MessagesMigrationTrait.php';

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211026162202 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $messages = [
        'USECASE.MORE_INFO' => [
            'default' => 'More info',
            'locales' => [
                'en-GB' => 'More info',
                'de-DE' => 'Mehr erfahren',
            ],
        ],
        'USECASE.PLAY_VIDEO' => [
            'default' => 'Play video',
            'locales' => [
                'en-GB' => 'Play video',
                'de-DE' => 'Video abspielen',
            ],
        ],
        'USECASE.RECOMMENDED_PRODUCTS' => [
            'default' => 'Recommended products for you',
            'locales' => [
                'en-GB' => 'Recommended products for you',
                'de-DE' => 'Für Sie empfohlene Produkte ',
            ],
        ],
        'USECASE.ADJUST_PRODUCTS' => [
            'default' => 'Adjust selection to your home',
            'locales' => [
                'en-GB' => 'Adjust selection to your home',
                'de-DE' => 'Auswahl an Ihr Zuhause anpassen',
            ],
        ],
        'PRODUCT.AVAILABLE_ONLINE' => [
            'default' => 'Online only',
            'locales' => [
                'en-GB' => 'Online Only',
                'de-DE' => 'Nur Online',
            ],
        ],
        'PRODUCT.AVAILABLE_RETAIL' => [
            'default' => 'At retail only',
            'locales' => [
                'en-GB' => 'At retail only',
                'de-DE' => 'Nur im Einzelhandel',
            ],
        ],
        'PRODUCT.PRICE_ON_REQUEST' => [
            'default' => 'Price on request',
            'locales' => [
                'en-GB' => 'Price on request',
                'de-DE' => 'Preis auf Anfrage',
            ],
        ],
        'PRODUCT.AVAILABLE_ONLINE_AND_RETAIL' => [
            'default' => 'Online and at retail',
            'locales' => [
                'en-GB' => 'Online and at retail',
                'de-DE' => 'Online und im Einzelhandel',
            ],
        ],
        'PRODUCT.ALTERNATIVE_PRODUCTS' => [
            'default' => 'Alternative products',
            'locales' => [
                'en-GB' => 'Alternative products',
                'de-DE' => 'Alternative Produkte ',
            ],
        ],
        'PRODUCT.TIP' => [
            'default' => 'Tip',
            'locales' => [
                'en-GB' => 'Tip',
                'de-DE' => 'Tipp',
            ],
        ],
        'PRODUCT.QUANTITY_ZERO_WARNING' => [
            'default' => 'Use case might not work without this product ',
            'locales' => [
                'en-GB' => 'Use case might not work without this product ',
                'de-DE' => 'Anwendungsfall funktioniert möglicherweise nicht ohne dieses Produkt ',
            ],
        ],
        'WISHLIST.TITLE' => [
            'default' => 'Wishlist',
            'locales' => [
                'en-GB' => 'Wishlist',
                'de-DE' => 'Merkzettel',
            ],
        ],
        'WISHLIST.USECASES' => [
            'default' => 'Use cases',
            'locales' => [
                'en-GB' => 'Use cases',
                'de-DE' => 'Lösungen',
            ],
        ],
        'WISHLIST.PRODUCTS' => [
            'default' => 'Products',
            'locales' => [
                'en-GB' => 'Products',
                'de-DE' => 'Produkte',
            ],
        ],
        'FILTER.RESULTS' => [
            'default' => 'Results',
            'locales' => [
                'en-GB' => 'Results',
                'de-DE' => 'Ergebnisse',
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
