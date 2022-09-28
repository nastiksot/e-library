<?php

declare(strict_types=1);

namespace DoctrineMigrations;

require_once __DIR__ . '/../MessagesMigrationTrait.php';

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211111173206 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $messages = [
        'NAVIGATION.DAY_WITH_SOMFY' => [
            'default' => 'Day with Somfy',
            'locales' => [
                'en-GB' => 'Day with Somfy',
                'de-DE' => 'Ein Tag mit Somfy',
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


