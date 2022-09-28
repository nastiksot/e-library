<?php

declare(strict_types=1);

namespace DoctrineMigrations;

require_once __DIR__ . '/../MessagesMigrationTrait.php';

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211123120807 extends AbstractMigration
{

    use MessagesMigrationTrait;

    private array $updateCodes = [
        'PRODUCT.PRICE_RANGE_NOTICE' => 'PRICE.RANGE.NOTICE',
        'PRODUCT.PRICE_RANGE'        => 'PRICE.RANGE.ON_REQUEST',
        'PRODUCT.PRICE_ON_REQUEST'   => 'PRICE.ON_REQUEST',
    ];


    private array $messages = [
        'PRICE.NOTICE.INSTALL' => [
            'default' => 'plus installation',
            'locales' => [
                'en-GB' => 'plus installation',
                'de-DE' => 'zzgl. Installation',
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
