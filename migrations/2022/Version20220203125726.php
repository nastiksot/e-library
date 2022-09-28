<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220203125726 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $messages = [

        'WISHLIST.SAVE.BUTTON.CLOSE' => [
            'default' => 'Close',
            'locales' => [
                'en-GB' => 'Close',
                'de-DE' => 'Schließen',
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
