<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220415125638 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $expiredMessages = [
        'DEALER_REQUEST.FORM.BUTTON.OPEN' => [
            'default' => 'Contact Somfy Dealer',
            'locales' => [
                'en_GB' => 'Contact Somfy Dealer',
                'de_DE' => 'Somfy Fachhändler kontaktieren',
            ],
        ],

        'DEALER_REQUEST.FORM.TITLE' => [
            'default' => 'Contact Somfy Dealer',
            'locales' => [
                'en_GB' => 'Contact Somfy Dealer',
                'de_DE' => 'Somfy Fachhändler Kontaktieren',
            ],
        ],

        'DEALER_REQUEST.FORM.BUTTON.SUBMIT' => [
            'default' => 'Send Your Request',
            'locales' => [
                'en_GB' => 'Send Your Request',
                'de_DE' => 'Senden Sie Ihre Anfrage',
            ],
        ],

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

        'LEAD_OUT.FIND_DEALER.TEXT.POSTFIX' => [
            'default' => 'which are only available from Dealers:',
            'locales' => [
                'en_GB' => 'which are only available from Dealers:',
                'de_DE' => ', die nur im Fachhandel erhältlich sind:',
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

    private array $messages = [
        'DEALER_REQUEST.FORM.BUTTON.OPEN' => [
            'default' => 'Contact your expert',
            'locales' => [
                'en_GB' => 'Contact your expert',
                'de_DE' => 'Fachhändler kontaktieren',
            ],
        ],

        'DEALER_REQUEST.FORM.TITLE' => [
            'default' => 'Contact Somfy Dealer',
            'locales' => [
                'en_GB' => 'Contact Somfy Dealer',
                'de_DE' => 'Fachhändler kontaktieren',
            ],
        ],

        'DEALER_REQUEST.FORM.BUTTON.SUBMIT' => [
            'default' => 'Send your request',
            'locales' => [
                'en_GB' => 'Send your request',
                'de_DE' => 'Anfrage senden',
            ],
        ],

        'LEAD_OUT.FIND_DEALER.TEXT.POSTFIX_SINGULAR' => [
            'default' => 'product that is only available in retail:',
            'locales' => [
                'en_GB' => 'product that is only available in retail:',
                'de_DE' => 'Produkt, das nur im Fachhandel erhätlich ist:',
            ],
        ],

        'LEAD_OUT.FIND_DEALER.TEXT.POSTFIX_PLURAL' => [
            'default' => 'products that are only available in retail:',
            'locales' => [
                'en_GB' => 'products that are only available in retail:',
                'de_DE' => 'Produkte, die nur im Fachhandel erhätlich sind:',
            ],
        ],

        'LEAD_OUT.BUY_ONLINE.TEXT.POSTFIX_SINGULAR' => [
            'default' => 'product that is only available Online:',
            'locales' => [
                'en_GB' => 'product that is only available Online:',
                'de_DE' => 'Produkt, das nur Online erhätlich ist:',
            ],
        ],

        'LEAD_OUT.BUY_ONLINE.TEXT.POSTFIX_PLURAL' => [
            'default' => 'products that are only available Online:',
            'locales' => [
                'en_GB' => 'products that are only available Online:',
                'de_DE' => 'Produkte, die nur Online erhätlich sind:',
            ],
        ],
    ];

    public function up(Schema $schema): void
    {
        $this->removeMessages($this->expiredMessages);

        $this->addMessages($this->messages);
    }

    public function down(Schema $schema): void
    {
        $this->removeMessages($this->messages);

        $this->addMessages($this->expiredMessages);
    }
}
