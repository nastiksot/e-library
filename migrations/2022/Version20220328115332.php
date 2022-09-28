<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220328115332 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $messages = [
        'PRODUCT_SET.BUTTON.SAVE' => [
            'default' => 'Add to wish list',
            'locales' => [
                'en_GB' => 'Add to wish list',
                'de_DE' => 'Zum Merkzettel hinzufügen',
            ],
        ],

        'PRODUCT_SET.BUTTON.SAVED' => [
            'default' => 'Added to wish list',
            'locales' => [
                'en_GB' => 'Added to wish list',
                'de_DE' => 'Zum Merkzettel hinzugefügt',
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
