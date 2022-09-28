<?php

declare(strict_types=1);

namespace DoctrineMigrations;

require_once __DIR__ . '/../MessagesMigrationTrait.php';

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211122092558 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $messages = [
        'DECISION_TREE.BUTTON.CLOSE' => [
            'default' => 'Close',
            'locales' => [
                'en-GB' => 'Close',
                'de-DE' => 'Schließen',
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
