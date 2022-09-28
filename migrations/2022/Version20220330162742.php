<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220406162742 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $expiredMessages = [
        'BUY_NOW.BUTTON.OPEN' => [
            'default' => 'Buy Now',
            'locales' => [
                'en_GB' => 'Buy Now',
                'de_DE' => 'Jetzt kaufen',
            ],
        ],

        'BUY_NOW.TITLE' => [
            'default' => 'Buy Now',
            'locales' => [
                'en_GB' => 'Buy Now',
                'de_DE' => 'Jetzt kaufen',
            ],
        ],

        'BUY_NOW.SOMFY.TITLE' => [
            'default' => 'Buy in the Somfy online shop',
            'locales' => [
                'en_GB' => 'Buy in the Somfy online shop',
                'de_DE' => 'Im Somfy Onlineshop kaufen',
            ],
        ],

        'BUY_NOW.DEALER.TITLE' => [
            'default' => 'Buy from your local specialist dealer',
            'locales' => [
                'en_GB' => 'Buy from your local specialist dealer',
                'de_DE' => 'Beim Fachhändler vor Ort kaufen',
            ],
        ],

        'BUY_NOW.BUTTON.DEALER' => [
            'default' => 'Find a Dealer',
            'locales' => [
                'en-GB' => 'Find a Dealer',
                'de-DE' => 'Fachhändler finden',
            ],
        ],

        'BUY_NOW.SOMFY.TEXT' => [
            'default' => 'There are 5 of 7 articles available online',
            'locales' => [
                'en_GB' => 'There are 5 of 7 articles available online',
                'de_DE' => 'Es sind 5 von 7 Artikeln online erhältlich',
            ],
        ],

        'BUY_NOW.BUTTON.SOMFY' => [
            'default' => 'To the Somfy Online Shop',
            'locales' => [
                'en_GB' => 'To the Somfy Online Shop',
                'de_DE' => 'Zum Somfy Onlineshop',
            ],
        ],

        'BUY_NOW.DEALER.TEXT' => [
            'default' => 'Request a free quote now for all items from your specialist dealer on site',
            'locales' => [
                'en_GB' => 'Request a free quote now for all items from your specialist dealer on site',
                'de_DE' => 'Fordern Sie jetzt kostenlos ein Angebot für alle Artikel bei Ihrem Fachhändler vor Ort an',
            ],
        ],
    ];

    private array $messages = [
        'LEAD_OUT.FIND_DEALER.BUTTON.EXTERNAL' => [
            'default' => 'Find a Somfy Dealer',
            'locales' => [
                'en_GB' => 'Find a Somfy Dealer',
                'de_DE' => 'Somfy Fachhändler Finden',
            ],
        ],

        'LEAD_OUT.FIND_DEALER.BUTTON.INTERNAL' => [
            'default' => 'Go to a Somfy Dealer',
            'locales' => [
                'en_GB' => 'Go to a Somfy Dealer',
                'de_DE' => 'Weiter Zur Somfy Händlersuche',
            ],
        ],

        'LEAD_OUT.FIND_DEALER.TITLE' => [
            'default' => 'Find a Somfy Dealer',
            'locales' => [
                'en_GB' => 'Find a Somfy Dealer',
                'de_DE' => 'Somfy Fachhändler Finden',
            ],
        ],

        'LEAD_OUT.FIND_DEALER.TEXT.PREFIX' => [
            'default' => 'Your Wishlist contains',
            'locales' => [
                'en_GB' => 'Your Wishlist contains',
                'de_DE' => 'Ihr Merkzettel enthält',
            ],
        ],
        'LEAD_OUT.FIND_DEALER.TEXT.POSTFIX' => [
            'default' => 'which are only available from Dealers:',
            'locales' => [
                'en_GB' => 'which are only available from Dealers:',
                'de_DE' => ', die nur im Fachhandel erhältlich sind:',
            ],
        ],

        'LEAD_OUT.FIND_DEALER.ZIP_CODE.LABEL' => [
            'default' => 'Enter zip code:',
            'locales' => [
                'en_GB' => 'Enter zip code:',
                'de_DE' => 'Postleitzahl eingeben:',
            ],
        ],

        'LEAD_OUT.FIND_DEALER.ZIP_CODE.PLACEHOLDER' => [
            'default' => 'Zip code',
            'locales' => [
                'en_GB' => 'Zip code',
                'de_DE' => 'Postleitzahl',
            ],
        ],

        //////////////////////////////////////

        'LEAD_OUT.TEXT.PRODUCT' => [
            'default' => 'Product',
            'locales' => [
                'en_GB' => 'Product',
                'de_DE' => 'Produkt',
            ],
        ],

        'LEAD_OUT.TEXT.PRODUCTS' => [
            'default' => 'Products',
            'locales' => [
                'en_GB' => 'Products',
                'de_DE' => 'Produkte',
            ],
        ],

        'LEAD_OUT.OR' => [
            'default' => 'Or',
            'locales' => [
                'en_GB' => 'Or',
                'de_DE' => 'Oder',
            ],
        ],

        'LEAD_OUT.NO_DATA' => [
            'default' => 'No data',
            'locales' => [
                'en_GB' => 'No data',
                'de_DE' => 'Keine Daten',
            ],
        ],

        ////////////////////////////////////////

        'LEAD_OUT.BUY_ONLINE.BUTTON.EXTERNAL' => [
            'default' => 'To the Somfy Online Shop',
            'locales' => [
                'en_GB' => 'To the Somfy Online Shop',
                'de_DE' => 'Zum Somfy Onlineshop',
            ],
        ],

        'LEAD_OUT.BUY_ONLINE.BUTTON.INTERNAL' => [
            'default' => 'Go to the Somfy online shop',
            'locales' => [
                'en_GB' => 'Go to the Somfy online shop',
                'de_DE' => 'Weiter zur Somfy Onlineshop',
            ],
        ],

        'LEAD_OUT.BUY_ONLINE.TITLE' => [
            'default' => 'To the Somfy Online Shop',
            'locales' => [
                'en_GB' => 'To the Somfy Online Shop',
                'de_DE' => 'Zum Somfy Onlineshop',
            ],
        ],

        'LEAD_OUT.BUY_ONLINE.TEXT.PREFIX' => [
            'default' => 'Your Wishlist contains',
            'locales' => [
                'en_GB' => 'Your Wishlist contains',
                'de_DE' => 'Ihr Merkzettel enthält',
            ],
        ],
        'LEAD_OUT.BUY_ONLINE.TEXT.POSTFIX' => [
            'default' => 'which are only available Online:',
            'locales' => [
                'en_GB' => 'which are only available Online:',
                'de_DE' => ', die nur Online erhältlich sind:',
            ],
        ],


    ];

    public function up(Schema $schema): void
    {
        $this->removeMessages($this->messages);
        $this->addMessages($this->messages);

        $this->removeMessages($this->expiredMessages);
    }

    public function down(Schema $schema): void
    {
        $this->removeMessages($this->messages);

        $this->addMessages($this->expiredMessages);
    }
}
