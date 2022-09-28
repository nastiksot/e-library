<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220316084118 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $messages = [
        'NAVIGATION.TAB.PLAY_VIDEO' => [
            'default' => 'Play Video',
            'locales' => [
                'en-GB' => 'Play Video',
                'de-DE' => 'Video abspielen',
            ],
        ],
    ];

    public function up(Schema $schema): void
    {
        // delete messages
        $this->removeMessages($this->messages);
    }

    public function down(Schema $schema): void
    {
        // recover messages
        $this->addMessages($this->messages);
    }
}
