<?php

declare(strict_types=1);

namespace DoctrineMigrations;

require_once __DIR__ . '/../MessagesMigrationTrait.php';

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211214151830 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $messages = [

        'FOOTER.PARTNERS.TITLE' => [
            'default' => 'Smart Home Partner',
            'locales' => [
                'en-GB' => 'Smart Home Partner',
                'de-DE' => 'Smart Home-Partner',
            ],
        ],

        'FOOTER.PARTNERS.INFO' => [
            'default' => 'More about partners',
            'locales' => [
                'en-GB' => 'More about partners',
                'de-DE' => 'Mehr Infos zu Partnern',
            ],
        ],

        'FOOTER.PARTNERS.MORE' => [
            'default' => 'Show More',
            'locales' => [
                'en-GB' => 'Show More',
                'de-DE' => 'Mehr Anzeigen',
            ],
        ],

        'FOOTER.PARTNERS.LESS' => [
            'default' => 'Show Less',
            'locales' => [
                'en-GB' => 'Show Less',
                'de-DE' => 'Weniger Anzeigen',
            ],
        ],


        'FOOTER.COMPATIBILITY_TOOL' => [
            'default' => 'Compatibility tool',
            'locales' => [
                'en-GB' => 'Compatibility tool',
                'de-DE' => 'Kompatibilität-Tool',
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
