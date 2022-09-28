<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220112150040 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $messages = [
        'PRODUCT.SELECT_PRODUCT' => [
            'default' => 'Select product',
            'locales' => [
                'en-GB' => 'Select product',
                'de-DE' => 'Artikel auswählen',
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
