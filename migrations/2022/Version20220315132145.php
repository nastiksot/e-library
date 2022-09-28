<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220315132145 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $messages = [
        'SIDEBAR.FILTERS.CLOSE' => [
            'default' => 'Close',
            'locales' => [
                'en_GB' => 'Close',
                'de_DE' => 'Ausblenden',
            ],
        ],
        'SIDEBAR.FILTERS.OPEN' => [
            'default' => 'Open',
            'locales' => [
                'en_GB' => 'Open',
                'de_DE' => 'Einblenden',
            ],
        ],
    ];

    private array $expiredMessages = [
        'SIDEBAR.FILTERS' => [
            'default' => 'Filters',
            'locales' => [
                'de-DE' => 'Filtern',
                'en-GB' => 'Filters',
            ],
        ],
    ];

    public function up(Schema $schema): void
    {
        $this->removeMessages($this->messages);
        $this->addMessages($this->messages);

        $this->removeMessages($this->expiredMessages);
    }

    public function down(Schema $schema): void
    {
        $this->removeMessages($this->messages);

        $this->addMessages($this->expiredMessages);
    }
}
