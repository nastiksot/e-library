<?php

declare(strict_types=1);

namespace DoctrineMigrations;

require_once __DIR__ . '/../MessagesMigrationTrait.php';

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211122105524 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $removeMessages = [
        'PRODUCT.SPECIAL_SHOP'       => [
            'default' => 'Only in special shops',
            'locales' => [
                'en-GB' => 'Only in special shops',
                'de-DE' => 'Nur im Fachhandel',
            ],
        ],
    ];

    private array $messages = [
        'PRODUCT.SPECIAL_SHOP'       => [
            'default' => 'Only in special shops',
            'locales' => [
                'en-GB' => 'Only in special shops',
                'de-DE' => 'Nur bei Fachhändler',
            ],
        ],
    ];


    public function up(Schema $schema): void
    {
        $this->removeMessages($this->removeMessages);
        $this->addMessages($this->messages);
    }

    public function down(Schema $schema): void
    {
        $this->removeMessages($this->messages);
        $this->addMessages($this->removeMessages);
    }
}
