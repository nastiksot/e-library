<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220504104233 extends AbstractMigration
{
    private array $data = [
        [
            'pages'    => '["home-planer"]',
            'position' => 1,

            'locales' => [
                'en_GB' => [
                    'title'       => '1-How to use the filter',
                    'description' => 'You can filter smart scenarios by topics, products, and application areas.',
                ],
                'de_DE' => [
                    'title'       => '1-So verwenden Sie den Filter',
                    'description' => 'Sie können intelligente Szenarien nach Themen, Produkten und Anwendungsbereichen filtern.',
                ],
            ],
        ],

        [
            'pages'    => '["home-planer"]',
            'position' => 2,

            'locales' => [
                'en_GB' => [
                    'title'       => '2-How to close the filter',
                    'description' => 'You can close or open the filter section smart scenarios by clicking here.',
                ],
                'de_DE' => [
                    'title'       => '2-So schließen Sie den Filter',
                    'description' => 'Sie können den Filterbereich intelligente Szenarien schließen oder öffnen, indem Sie hier klicken.',
                ],
            ],
        ],

        [
            'pages'    => '["home-planer", "wish-list"]',
            'position' => 3,

            'locales' => [
                'en_GB' => [
                    'title'       => '3-How to change the house view',
                    'description' => 'You can change the house view by clicking on each tab on top or two rotation icons on the sides.',
                ],
                'de_DE' => [
                    'title'       => '3-So ändern Sie die Hausansicht',
                    'description' => 'Sie können die Hausansicht ändern, indem Sie oben auf die einzelnen ' .
                        'Registerkarten oder auf die beiden Rotationssymbole an den Seiten klicken.',
                ],
            ],
        ],

        [
            'pages'    => '["home-planer"]',
            'position' => 4,

            'locales' => [
                'en_GB' => [
                    'title'       => '4-How to open a use case',
                    'description' => 'You can open a use case in 2 ways:<br>' .
                        '1 - Hover your mouse on the use case you want to open in the right section and click on learn more.<br>' .
                        '2 - Click on the use case bubble in the configurator.',
                ],
                'de_DE' => [
                    'title'       => '4-So öffnen Sie einen Anwendungsfall',
                    'description' => 'Sie können einen Anwendungsfall auf zwei Arten öffnen:<br>' .
                        '1 - Bewegen Sie die Maus über den Anwendungsfall, den Sie im rechten Bereich öffnen möchten, und klicken Sie auf „Mehr erfahren“.<br>' .
                        '2 - Klicken Sie im Konfigurator auf die Anwendungsfallblase.',
                ],
            ],
        ],

        [
            'pages'    => '["home-planer", "wish-list", "day-with-somfy"]',
            'position' => 5,

            'locales' => [
                'en_GB' => [
                    'title'       => '5-How to personalize the product list',
                    'description' => 'You can adapt products to your home by clicking on this button and completing the questionnaire.',
                ],
                'de_DE' => [
                    'title'       => '5-So personalisieren Sie die Produktliste',
                    'description' => 'Sie können Produkte an Ihr Zuhause anpassen, indem Sie auf diese Schaltfläche klicken und den Fragebogen ausfüllen.',
                ],
            ],
        ],

        [
            'pages'    => '["home-planer", "wish-list", "day-with-somfy"]',
            'position' => 6,

            'locales' => [
                'en_GB' => [
                    'title'       => '6-How to display more product information',
                    'description' => 'You can find more information about each product by clicking on this Info icon.',
                ],
                'de_DE' => [
                    'title'       => '6-So zeigen Sie weitere Produktinformationen an',
                    'description' => 'Weitere Informationen zu jedem Produkt finden Sie, indem Sie auf dieses Info-Symbol klicken.',
                ],
            ],
        ],

        [
            'pages'    => '["home-planer", "wish-list", "day-with-somfy"]',
            'position' => 7,

            'locales' => [
                'en_GB' => [
                    'title'       => '7-How to add a use case to the wish list',
                    'description' => 'By clicking on this button, you can add a use case to the wish list.<br>' .
                        'Because every home is different, there is an option to adapt the selection to your local conditions before you add it to your wish list.',
                ],
                'de_DE' => [
                    'title'       => '7-So fügen Sie der Wunschliste einen Anwendungsfall hinzu',
                    'description' => 'Durch Klicken auf diesen Button können Sie einen Anwendungsfall zur Wunschliste hinzufügen.<br>' .
                        'Da jedes Haus anders ist, besteht die Möglichkeit, die Auswahl an Ihre örtlichen Gegebenheiten anzupassen, bevor Sie es auf Ihre Wunschliste setzen.',
                ],
            ],
        ],

        [
            'pages'    => '["day-with-somfy"]',
            'position' => 8,

            'locales' => [
                'en_GB' => [
                    'title'       => '8-How to open the wishlist',
                    'description' => 'By clicking the wishlist on the top navigation bar, you can access to your added use cases and products.',
                ],
                'de_DE' => [
                    'title'       => '8-So öffnen Sie die Wunschliste',
                    'description' => 'Durch Klicken auf die Wunschliste in der oberen Navigationsleiste können Sie auf Ihre hinzugefügten Anwendungsfälle und Produkte zugreifen.',
                ],
            ],
        ],

        [
            'pages'    => '["wish-list"]',
            'position' => 9,

            'locales' => [
                'en_GB' => [
                    'title'       => '9-How to Buy products in the wish list',
                    'description' => 'You can buy products from your wishlist by clicking on these two buttons in the wishlist:<br>' .
                        '1-going to our Online shop<br>' .
                        '(some are available only online).<br>' .
                        '2-reach the nearest dealer to your location<br>' .
                        '(some products are available only in dealer shops).',
                ],
                'de_DE' => [
                    'title'       => '9-So kaufen Sie Produkte in der Wunschliste',
                    'description' => 'Sie können Produkte von Ihrer Wunschliste kaufen, indem Sie in der Wunschliste auf diese beiden Schaltflächen klicken:<br>' .
                        '1-zu unserem Online-Shop gehen<br>' .
                        '(einige sind nur online verfügbar).<br>' .
                        '2-erreichen Sie den nächstgelegenen Händler zu Ihrem Standort<br>' .
                        '(einige Produkte sind nur in Händlershops erhältlich).',
                ],
            ],
        ],

        [
            'pages'    => '["home-planer", "wish-list", "day-with-somfy"]',
            'position' => 10,

            'locales' => [
                'en_GB' => [
                    'title'       => '10-How to share the wish list',
                    'description' => 'After you add or personalized a use case, you can share your scenario with ' .
                        'someone else. Click on the button and copy the link.',
                ],
                'de_DE' => [
                    'title'       => '10-So teilen Sie die Wunschliste',
                    'description' => 'Nachdem Sie einen Anwendungsfall hinzugefügt oder personalisiert haben, ' .
                        'können Sie Ihr Szenario mit jemand anderem teilen. Klicken Sie auf die Schaltfläche und ' .
                        'kopieren Sie den Link.',
                ],
            ],
        ],

        [
            'pages'    => '["home-planer", "wish-list", "day-with-somfy"]',
            'position' => 11,

            'locales' => [
                'en_GB' => [
                    'title'       => '11-How to save the wish list',
                    'description' => 'After you add or personalized a use case to the wish list, ' .
                        'you can save your selection by clicking this button. Soon will receive an email ' .
                        'with the link to your configuration.',
                ],
                'de_DE' => [
                    'title'       => '11-So speichern Sie die Wunschliste',
                    'description' => 'Nachdem Sie einen Anwendungsfall zur Wunschliste hinzugefügt oder ' .
                        'personalisiert haben, können Sie Ihre Auswahl speichern, indem Sie auf diese Schaltfläche ' .
                        'klicken. In Kürze erhalten Sie eine E-Mail mit dem Link zu Ihrer Konfiguration.',
                ],
            ],
        ],
    ];

    public function addDescriptivePanels(array $descriptivePanels): void
    {
        $descriptivePanelsSql = '
            INSERT INTO `descriptive_panels`
                SET
                    `pages` = :pages,
                    `position` = :position
        ';

        $descriptivePanelTranslationsSql = '
            INSERT INTO `descriptive_panel_translations`
                SET
                    `translatable_id` = :translatableId,
                    `locale` = :locale,
                    `title` = :title,
                    `description` = :description
        ';

        foreach ($descriptivePanels as $item) {
            $this->connection->executeStatement(
                $descriptivePanelsSql,
                ['pages' => $item['pages'], 'position' => $item['position']]
            );
            $translatableId = $this->connection->lastInsertId();

            foreach ($item['locales'] as $locale => $localeItem) {
                $this->connection->executeStatement(
                    $descriptivePanelTranslationsSql,
                    [
                        'translatableId' => $translatableId,
                        'locale'         => $locale,
                        'title'          => $localeItem['title'],
                        'description'    => $localeItem['description'],
                    ]
                );
            }
        }
    }

    public function up(Schema $schema): void
    {
        $this->addDescriptivePanels($this->data);
    }

    public function down(Schema $schema): void
    {
        $this->connection->executeStatement('DELETE FROM `descriptive_panels`');
    }
}
