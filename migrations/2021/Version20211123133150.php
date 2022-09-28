<?php

declare(strict_types=1);

namespace DoctrineMigrations;

require_once __DIR__ . '/../MessagesMigrationTrait.php';

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211123133150 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $updateCodes = [
        'PRICE_RANGE' => 'PRICE.RANGE.VALUE',
    ];

    public function up(Schema $schema): void
    {
        foreach ($this->updateCodes as $oldCode => $newCode) {
            $this->addSql(
                "UPDATE `messages` SET `code` = :newCode WHERE `code` = :oldCode",
                [
                    'newCode' => $newCode,
                    'oldCode' => $oldCode,
                ]
            );
        }
    }

    public function down(Schema $schema): void
    {
        foreach ($this->updateCodes as $oldCode => $newCode) {
            $this->addSql(
                "UPDATE `messages` SET `code` = :oldCode WHERE `code` = :newCode",
                [
                    'newCode' => $newCode,
                    'oldCode' => $oldCode,
                ]
            );
        }
    }
}
