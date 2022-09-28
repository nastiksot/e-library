<?php

declare(strict_types=1);

namespace DoctrineMigrations;

require_once __DIR__ . '/../MessagesMigrationTrait.php';

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211115090817 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $messages = [

        'VIDEO_PLAYER.BUTTON.CLOSE' => [
            'default' => 'Close',
            'locales' => [
                'en-GB' => 'Close',
                'de-DE' => 'Schließen',
            ],
        ],

        'PRODUCT_SET.BUTTON.SAVE' => [
            'default' => 'Save',
            'locales' => [
                'en-GB' => 'Save',
                'de-DE' => 'Speichern',
            ],
        ],

        'DESCRIPTION_TEXT.BUTTON.READ' => [
            'default' => 'More',
            'locales' => [
                'en-GB' => 'More',
                'de-DE' => 'weiter',
            ],
        ],
        'DESCRIPTION_TEXT.BUTTON.LESS' => [
            'default' => 'Less',
            'locales' => [
                'en-GB' => 'Less',
                'de-DE' => 'kleiner',
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
