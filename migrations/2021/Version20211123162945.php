<?php

declare(strict_types=1);

namespace DoctrineMigrations;

require_once __DIR__ . '/../MessagesMigrationTrait.php';

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211123162945 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $messages = [

        'BUY_NOW.BUTTON.OPEN' => [
            'default' => 'Buy Now',
            'locales' => [
                'en-GB' => 'Buy Now',
                'de-DE' => 'Jetzt kaufen',
            ],
        ],

        'BUY_NOW.TITLE' => [
            'default' => 'Buy Now',
            'locales' => [
                'en-GB' => 'Buy Now',
                'de-DE' => 'Jetzt kaufen',
            ],
        ],

        'BUY_NOW.SOMFY.TITLE' => [
            'default' => 'Buy in the Somfy online shop',
            'locales' => [
                'en-GB' => 'Buy in the Somfy online shop',
                'de-DE' => 'Im Somfy Onlineshop kaufen',
            ],
        ],

        'BUY_NOW.SOMFY.TEXT' => [
            'default' => 'There are 5 of 7 articles available online',
            'locales' => [
                'en-GB' => 'There are 5 of 7 articles available online',
                'de-DE' => 'Es sind 5 von 7 Artikeln online erhältlich',
            ],
        ],

        'BUY_NOW.BUTTON.SOMFY' => [
            'default' => 'To the Somfy Online Shop',
            'locales' => [
                'en-GB' => 'To the Somfy Online Shop',
                'de-DE' => 'Zum Somfy Onlineshop',
            ],
        ],

        'BUY_NOW.DEALER.TITLE' => [
            'default' => 'Buy from your local specialist dealer',
            'locales' => [
                'en-GB' => 'Buy from your local specialist dealer',
                'de-DE' => 'Beim Fachhändler vor Ort kaufen',
            ],
        ],

        'BUY_NOW.DEALER.TEXT' => [
            'default' => 'Request a free quote now for all items from your specialist dealer on site',
            'locales' => [
                'en-GB' => 'Request a free quote now for all items from your specialist dealer on site',
                'de-DE' => 'Fordern Sie jetzt kostenlos ein Angebot für alle Artikel bei Ihrem Fachhändler vor Ort an',
            ],
        ],

        'BUY_NOW.BUTTON.DEALER' => [
            'default' => 'Find a Dealer',
            'locales' => [
                'en-GB' => 'Find a Dealer',
                'de-DE' => 'Fachhändler finden',
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
