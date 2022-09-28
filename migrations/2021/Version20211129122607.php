<?php

declare(strict_types=1);

namespace DoctrineMigrations;

require_once __DIR__ . '/../MessagesMigrationTrait.php';

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211129122607 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $messages = [

        'PRICE.RANGE.ON_REQUEST' => [
            'default' => '{price} - {price_end}',
            'locales' => [
                'en-GB' => '{price} - {price_end}',
                'de-DE' => '{price} - {price_end}',
            ],
        ],

        'PRICE.RANGE.VALUE' => [
            'default' => '{price} - {price_end}',
            'locales' => [
                'en-GB' => '{price} - {price_end}',
                'de-DE' => '{price} - {price_end}',
            ],
        ],

    ];

    public function up(Schema $schema): void
    {
        // remove the currency symbol from messages
        $this->removeMessages($this->messages);
        $this->addMessages($this->messages);
    }

    public function down(Schema $schema): void
    {
        $this->removeMessages($this->messages);
        $this->addMessages($this->messages);
    }
}
