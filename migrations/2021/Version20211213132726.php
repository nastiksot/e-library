<?php

declare(strict_types=1);

namespace DoctrineMigrations;

require_once __DIR__ . '/../MessagesMigrationTrait.php';

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211213132726 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $messages = [

        'PRICE.INCLUDES_VAT' => [
            'default' => 'incl. VAT',
            'locales' => [
                'en-GB' => 'incl. VAT',
                'de-DE' => 'inkl. MwSt',
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
