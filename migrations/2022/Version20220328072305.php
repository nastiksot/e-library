<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220328072305 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $messages = [
        'PRICE.POSTFIX' => [
            'default' => 'per item*',
            'locales' => [
                'en_GB' => 'per item*',
                'de_DE' => 'pro Stück*',
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
        $this->addMessages($this->messages);
    }
}
