<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220203075439 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $messages = [

        'USER.ACCOUNT.HELLO' => [
            'default' => 'Hello {first_name} {last_name}!',
            'locales' => [
                'en-GB' => 'Hello {first_name} {last_name}!',
                'de-DE' => 'Hallo {first_name} {last_name}!',
            ],
        ],

        'USER.ACCOUNT.MENU.ACCOUNT' => [
            'default' => 'Account Management',
            'locales' => [
                'en-GB' => 'Account Management',
                'de-DE' => 'Kontoverwaltung',
            ],
        ],

        'USER.ACCOUNT.LANGUAGE' => [
            'default' => 'language',
            'locales' => [
                'en-GB' => 'language',
                'de-DE' => 'Sprache',
            ],
        ],



    ];

    public function up(Schema $schema): void
    {
        $this->removeMessages(['USER.ACCOUNT.MENU.USER_DATA' => 'USER.ACCOUNT.MENU.USER_DATA']);
        $this->removeMessages($this->messages);
        $this->addMessages($this->messages);
    }

    public function down(Schema $schema): void
    {
        $this->removeMessages($this->messages);
    }

}
