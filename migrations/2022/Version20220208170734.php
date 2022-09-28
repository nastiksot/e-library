<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220208170734 extends AbstractMigration
{

    use MessagesMigrationTrait;

    private array $messages = [
        'USECASE.RESET_REPLACEMENTS' => [
            'default' => 'Reset Changes',
            'locales' => [
                'en-GB' => 'Reset Changes',
                'de-DE' => 'Anpassungen zurücksetzen',
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
