<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220119120354 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $messages = [
        'PRODUCT.OPTIONAL_PRODUCTS.TITLE' => [
            'default' => 'Optional products',
            'locales' => [
                'en-GB' => 'Optional products',
                'de-DE' => 'Empfohlene Produkte',
            ],
        ],
        'PRODUCT.OPTIONAL_PRODUCTS.INFO' => [
            'default' => 'Optional products',
            'locales' => [
                'en-GB' => 'Optional products',
                'de-DE' => 'Empfohlene Produkte',
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
