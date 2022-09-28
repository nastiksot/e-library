<?php

declare(strict_types=1);

namespace DoctrineMigrations;

require_once __DIR__ . '/../MessagesMigrationTrait.php';

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211104135352 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $messages = [
        'DECISION_TREE.TITLE' => [
            'default' => 'Adapt selection to your home',
            'locales' => [
                'en-GB' => 'Adapt selection to your home',
                'de-DE' => 'Auswahl an dein Zuhause anpassen',
            ],
        ],

        'DECISION_TREE.BUTTON.SUBMIT' => [
            'default' => 'Apply filter to selected products',
            'locales' => [
                'en-GB' => 'Apply filter to selected products',
                'de-DE' => 'Filter Auf Ausgewählte Produkte Anwenden',
            ],
        ],

        'DECISION_TREE.BUTTON.RESET' => [
            'default' => 'Reset All',
            'locales' => [
                'en-GB' => 'Reset All',
                'de-DE' => 'Alles zurücksetzen',
            ],
        ],

        'DECISION_TREE.TIP' => [
            'default' => 'Tip:',
            'locales' => [
                'en-GB' => 'Tip:',
                'de-DE' => 'Tip:',
            ],
        ],

        'DECISION_TREE.MESSAGE.SUCCESS' => [
            'default' => 'Selection successfully adapted to your home',
            'locales' => [
                'en-GB' => 'Selection successfully adapted to your home',
                'de-DE' => 'Auswahl erfolgreich an Ihr Zuhause angepasst',
            ],
        ],

        'DECISION_TREE.MESSAGE.NEGATIVE' => [
            'default' => 'Unfortunately, no suitable products are available for your selection.',
            'locales' => [
                'en-GB' => 'Unfortunately, no suitable products are available for your selection.',
                'de-DE' => 'Für Ihre Auswahl stehen leider keine passenden Produkte zur Verfügung.',
            ],
        ],

        'DECISION_TREE.MESSAGE.CHANGE_OR_CONTACT.PREFIX' => [
            'default' => 'Please adjust your selection or contact a',
            'locales' => [
                'en-GB' => 'Please adjust your selection or contact a',
                'de-DE' => 'Bitte passen Sie Ihre Auswahl an oder kontaktieren Sie a',
            ],
        ],

        'DECISION_TREE.MESSAGE.CHANGE_OR_CONTACT.LABEL' => [
            'default' => 'Somfy specialist partner',
            'locales' => [
                'en-GB' => 'Somfy specialist partner',
                'de-DE' => 'Somfy Fachpartner',
            ],
        ],

        'DECISION_TREE.MESSAGE.CHANGE_OR_CONTACT.SUFFIX' => [
            'default' => 'for further questions.',
            'locales' => [
                'en-GB' => 'for further questions.',
                'de-DE' => 'Für weitere Fragen.',
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
