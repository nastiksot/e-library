<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220203145758 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $messages = [
        'USER.REGISTER_CONFIRM.BUTTON.LOGIN' => [
            'default' => 'Login',
            'locales' => [
                'en-GB' => 'Login',
                'de-DE' => 'Einloggen',
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
