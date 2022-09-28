<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220317130608 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $messages = [
        'USER.WISHLISTS.LABEL.NAME' => [
            'default' => 'Name',
            'locales' => [
                'en_GB' => 'Name',
                'de_DE' => 'Name',
            ],
        ],

        'USER.WISHLISTS.BUTTON.SAVE' => [
            'default' => 'Save',
            'locales' => [
                'en_GB' => 'Save',
                'de_DE' => 'Speichern',
            ],
        ],

        'USER.WISHLISTS.UPDATE.TITLE' => [
            'default' => 'WishList management ',
            'locales' => [
                'en_GB' => 'WishList management ',
                'de_DE' => 'WishList-Verwaltung ',
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
