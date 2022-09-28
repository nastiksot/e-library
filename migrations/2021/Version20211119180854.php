<?php

declare(strict_types=1);

namespace DoctrineMigrations;

require_once __DIR__ . '/../MessagesMigrationTrait.php';

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211119180854 extends AbstractMigration
{

    use MessagesMigrationTrait;

    private array $removeMessages = [
        'PRODUCT.PRICE_REQUEST' => [
            'default' => 'Price on request',
            'locales' => [
                'en-GB' => 'Price on request',
                'de-DE' => 'Preis auf Anfrage',
            ],
        ],
    ];

    private array $messages = [
        'PRODUCT.PRICE_RANGE'        => [
            'default' => 'Price on request <br/>(from {price} € - to {price_end} €)',
            'locales' => [
                'en-GB' => 'Price on request <br/>(from {price} € - to {price_end} €)',
                'de-DE' => 'Preis auf Anfrage <br/>(von {price} € - bis {price_end} €)',
            ],
        ],
        'PRODUCT.PRICE_RANGE_NOTICE' => [
            'default' => 'The final price depends on the installation situation.',
            'locales' => [
                'en-GB' => 'The final price depends on the installation situation.',
                'de-DE' => 'Der entgültige Preis ist abhängig von der Einbausituation.',
            ],
        ],
        'PRODUCT.SPECIAL_SHOP'       => [
            'default' => 'Only in special shops',
            'locales' => [
                'en-GB' => 'Only in special shops',
                'de-DE' => 'Nur im Fachhandel',
            ],
        ],

    ];


    public function up(Schema $schema): void
    {
        $this->removeMessages($this->removeMessages);
        $this->addMessages($this->messages);
    }

    public function down(Schema $schema): void
    {
        $this->removeMessages($this->messages);
        $this->addMessages($this->removeMessages);
    }
}
