<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

require_once __DIR__ . '/../MessagesMigrationTrait.php';

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220217175652 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $messages = [

        'DEALER_REQUEST.FORM.LABEL.CONTACT_NAME' => [
            'default' => 'Name',
            'locales' => [
                'en_GB' => 'Name',
                'de_DE' => 'Name',
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
