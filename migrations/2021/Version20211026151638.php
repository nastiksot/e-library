<?php

declare(strict_types=1);

namespace DoctrineMigrations;

require_once __DIR__ . '/../MessagesMigrationTrait.php';

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211026151638 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $messages = [

        'USER.ACCEPT_PRIVACY_POLICY.PREFIX' => [
            'default' => 'I have read the',
            'locales' => [
                'en-GB' => 'I have read the',
                'de-DE' => 'Ich habe die',
            ],
        ],

        'USER.ACCEPT_PRIVACY_POLICY.LABEL' => [
            'default' => 'privacy policy',
            'locales' => [
                'en-GB' => 'privacy policy',
                'de-DE' => 'Datenschutzrichtlinie',
            ],
        ],

        'USER.ACCEPT_PRIVACY_POLICY.SUFFIX' => [
            'default' => 'and I agree to it.',
            'locales' => [
                'en-GB' => 'and I agree to it.',
                'de-DE' => 'gelesen und stimme ihnen zu.',
            ],
        ],

        'USER.ACCEPT_PRIVACY_POLICY.TITLE' => [
            'default' => 'Read the privacy policy',
            'locales' => [
                'en-GB' => 'Read the privacy policy',
                'de-DE' => 'Lesen Sie die Datenschutzerklärung',
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
