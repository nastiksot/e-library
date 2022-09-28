<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220327132319 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $messages = [
        'PRODUCT.OPTIONAL_PRODUCTS.INFO' => [
            'default' => 'Optional products',
            'locales' => [
                'en_GB' => 'Optional products',
                'de_DE' => 'Empfohlene Produkte',
            ],
        ],

    ];

    public function up(Schema $schema): void
    {
        $this->removeMessages($this->messages);
    }

    public function down(Schema $schema): void
    {
        $this->addMessages($this->messages);
    }
}
