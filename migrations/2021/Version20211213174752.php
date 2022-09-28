<?php

declare(strict_types=1);

namespace DoctrineMigrations;

require_once __DIR__ . '/../MessagesMigrationTrait.php';

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211213174752 extends AbstractMigration
{

    use MessagesMigrationTrait;

    private array $messages = [
        'SLIDER.BUTTON.HOME_PLANER' => [
            'default' => 'Go to the Smart Home Planner',
            'locales' => [
                'en-GB' => 'Go to the Smart Home Planner',
                'de-DE' => 'Weiter Zum Smart Home Planer',
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
