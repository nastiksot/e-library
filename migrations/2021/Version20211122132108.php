<?php

declare(strict_types=1);

namespace DoctrineMigrations;

require_once __DIR__ . '/../MessagesMigrationTrait.php';

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211122132108 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $messages = [
        'PRICE_RANGE' => [
            'default' => 'from {price} € - to {price_end} €',
            'locales' => [
                'en-GB' => 'from {price} € - to {price_end} €',
                'de-DE' => 'von {price} € - bis {price_end} €',
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
