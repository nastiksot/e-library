<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220327079999 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $messages = [
        'USER.LOGIN.MESSAGE.REGISTER' => [
            'default' => 'Create a New Account',
            'locales' => [
                'en_GB' => 'Create a New Account',
                'de_DE' => 'Neues Konto anlegen',
            ],
        ],

        'USER.REGISTER.TITLE' => [
            'default' => 'Create a New Account',
            'locales' => [
                'en_GB' => 'Create a New Account',
                'de_DE' => 'Neues Konto anlegen',
            ],
        ],

        'USER.REGISTER.SUB_TITLE' => [
            'default' => 'Please enter your email address',
            'locales' => [
                'en_GB' => 'Please enter your email address',
                'de-DE' => 'Geben sie ihre E-Mailadresse ein',
            ],
        ],

        'USER.REGISTER.DONE' => [
            'default' => 'Verify your account',
            'locales' => [
                'en_GB' => 'Verify your account',
                'de_DE' => 'Verifizieren Sie Ihr Konto',
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
