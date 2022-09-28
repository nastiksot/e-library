<?php

declare(strict_types=1);

namespace DoctrineMigrations;

require_once __DIR__ . '/../MessagesMigrationTrait.php';

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211130160117 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $updateCodes = [
        'PRODUCT.QUANTITY_ZERO_WARNING' => 'PRODUCT.WARNING.QUANTITY_ZERO',
    ];

    private array $messages = [
        'PRODUCT.WARNING.DUPLICATE' => [
            'default' => 'This product is duplicated in another use case',
            'locales' => [
                'en-GB' => 'This product is duplicated in another use case',
                'de-DE' => 'Dieses Produkt wird in einem anderen Anwendungsfall dupliziert',
            ],
        ],
    ];

    public function up(Schema $schema): void
    {
        $this->addMessages($this->messages);
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
        $this->removeMessages($this->messages);
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
