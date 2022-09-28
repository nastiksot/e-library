<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220121132152 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $messages = [
        'GENERAL.NOTICE' => [
            'default' => 'Notice:',
            'locales' => [
                'en-GB' => 'Notice:',
                'de-DE' => 'Hinweis:',
            ],
        ],
        'PRODUCT_SET.ATTEMPT_ADJUST_BEFORE_ADD_TO_WISH_LIST.INFO' => [
            'default' => 'Do you want to customize products to your home before adding to the wishlist?',
            'locales' => [
                'en-GB' => 'Do you want to customize products to your home before adding to the wishlist?',
                'de-DE' => 'Möchten Sie Produkte an Ihr Zuhause anpassen, bevor Sie sie zur Wunschliste hinzufügen?',
            ],
        ],
        'PRODUCT_SET.ATTEMPT_ADJUST_BEFORE_ADD_TO_WISH_LIST.BUTTON.YES' => [
            'default' => 'Yes, customize products',
            'locales' => [
                'en-GB' => 'Yes, customize products',
                'de-DE' => 'Ja, Produkte anpassen',
            ],
        ],
        'PRODUCT_SET.ATTEMPT_ADJUST_BEFORE_ADD_TO_WISH_LIST.BUTTON.NO' => [
            'default' => 'No, add to wishlist',
            'locales' => [
                'en-GB' => 'No, add to wishlist',
                'de-DE' => 'Nein, zur Merkzettel hinzufügen',
            ],
        ],
        'PRODUCT_SET.ATTEMPT_ADJUST_BEFORE_ADD_TO_WISH_LIST.NOTICE.SUCCESS_PREFIX' => [
            'default' => 'Products adapted to your home and also added to your',
            'locales' => [
                'en-GB' => 'Products adapted to your home and also added to your',
                'de-DE' => 'Produkte, die an Ihr Zuhause angepasst und auch zu Ihrer ',
            ],
        ],
        'PRODUCT_SET.ATTEMPT_ADJUST_BEFORE_ADD_TO_WISH_LIST.NOTICE.SUCCESS_SUFFIX' => [
            'default' => '.',
            'locales' => [
                'en-GB' => '.',
                'de-DE' => 'hinzugefügt wurden.',
            ],
        ],
        'PRODUCT_SET.ATTEMPT_ADJUST_BEFORE_ADD_TO_WISH_LIST.NOTICE.SUCCESS_LINK_LABEL' => [
            'default' => 'Wishlist',
            'locales' => [
                'en-GB' => 'Wishlist',
                'de-DE' => 'Merkzettel',
            ],
        ],

        'PRODUCT_SET.ATTEMPT_ADJUST_BEFORE_ADD_TO_WISH_LIST.NOTICE.SUCCESS_LINK_TITLE' => [
            'default' => 'Wishlist',
            'locales' => [
                'en-GB' => 'Wishlist',
                'de-DE' => 'Merkzettel',
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
