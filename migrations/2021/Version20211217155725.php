<?php

declare(strict_types=1);

namespace DoctrineMigrations;

require_once __DIR__ . '/../MessagesMigrationTrait.php';

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211217155725 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $messages = [

        'PRODUCT_SET.DECISION_RESULT.TITLE' => [
            'default' => 'Summary of Сhanges',
            'locales' => [
                'en-GB' => 'Summary of Сhanges',
                'de-DE' => 'Zusammenfassung der Änderungen',
            ],
        ],

        'PRODUCT_SET.DECISION_RESULT.DELETED' => [
            'default' => 'Deleted Article',
            'locales' => [
                'en-GB' => 'Deleted Article',
                'de-DE' => 'Gelöschter Artikel',
            ],
        ],

        'PRODUCT_SET.DECISION_RESULT.REPLACED' => [
            'default' => 'Replaced Article (s)',
            'locales' => [
                'en-GB' => 'Replaced Article (s)',
                'de-DE' => 'Ersetzter Artikel',
            ],
        ],

        'PRODUCT_SET.DECISION_RESULT.REPLACED_BEFORE' => [
            'default' => 'Article Before Customization',
            'locales' => [
                'en-GB' => 'Article Before Customization',
                'de-DE' => 'Artikel vor der Anpassung',
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
