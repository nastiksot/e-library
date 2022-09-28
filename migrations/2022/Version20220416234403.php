<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220416234403 extends AbstractMigration
{
    use MessagesMigrationTrait;


    private array $messages = [
        'LEAD_OUT.NOTE' => [
            'default' => '<b>Note:</b> Please note that we - like many other industries - are currently affected by the global shortage of electronic components, which makes our production enormously difficult and therefore affects our product availability and delivery times. We thank you for your understanding!',
            'locales' => [
                'en_GB' => '<b>Note:</b> Please note that we - like many other industries - are currently affected by the global shortage of electronic components, which makes our production enormously difficult and therefore affects our product availability and delivery times. We thank you for your understanding!',
                'de_DE' => '<b>Hinweis:</b> Bitte beachten Sie, dass auch wir – wie zahlreiche andere Branchen – aktuell von der weltweiten Knappheit von Elektronikbauteilen betroffen sind, was unsere Produktion enorm erschwert und sich daher auf unsere Produktverfügbarkeiten und Lieferzeiten auswirkt. Wir danken Ihnen für Ihr Verständnis!',
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
