<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220310163854 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $messages = [
        'DEALER_REQUEST.RESTORE_REQUEST.SUCCESS.PREFIX' => [
            'default' => "'s request was restore successfully. You can find it",
            'locales' => [
                'en_GB' => "'s request was restore successfully. You can find it",
                'de_DE' => 's Anfrage wurde erfolgreich wiederhergestellt. Du kannst es finden',
            ],
        ],

        'DEALER_REQUEST.RESTORE_REQUEST.SUCCESS.REQUESTS_LINK' => [
            'default' => 'here',
            'locales' => [
                'en_GB' => 'here',
                'de_DE' => 'hier',
            ],
        ],

        'DEALER_REQUEST.RESTORE_REQUEST.SUCCESS.POSTFIX' => [
            'default' => '.',
            'locales' => [
                'en_GB' => '.',
                'de_DE' => '.',
            ],
        ],

        //-----------

        'DEALER_REQUEST.THERE_ARE_NO_REQUESTS' => [
            'default' => 'There are no Customer Requests',
            'locales' => [
                'en_GB' => 'There are no Customer Requests',
                'de_DE' => 'Es gibt keine Kundenumfragen',
            ],
        ],

        //-----------

        'DEALER_REQUEST.ACTION.ARCHIVE_REQUESTS' => [
            'default' => 'Archive requests',
            'locales' => [
                'en_GB' => 'Archive requests',
                'de_DE' => 'Anfragen archivieren',
            ],
        ],

        'DEALER_REQUEST.ACTION.ARCHIVE_REQUEST' => [
            'default' => 'Archive request',
            'locales' => [
                'en_GB' => 'Archive request',
                'de_DE' => 'Anfrage archivieren',
            ],
        ],

        'DEALER_REQUEST.ACTION.DELETE_REQUESTS' => [
            'default' => 'Delete requests',
            'locales' => [
                'en_GB' => 'Delete requests',
                'de_DE' => 'Anfragen löschen',
            ],
        ],

        'DEALER_REQUEST.ACTION.RESTORE_REQUEST' => [
            'default' => 'Restore request',
            'locales' => [
                'en_GB' => 'Restore request',
                'de_DE' => 'Anforderung wiederherstellen',
            ],
        ],

        'DEALER_REQUEST.ACTION.RESTORE_REQUESTS' => [
            'default' => 'Restore requests',
            'locales' => [
                'en_GB' => 'Restore requests',
                'de_DE' => 'Anfragen wiederherstellen',
            ],
        ],

        //-----------

        'DEALER_REQUEST.DELETE_REQUESTS.TITLE' => [
            'default' => 'Delete requests',
            'locales' => [
                'en_GB' => 'Delete requests',
                'de_DE' => 'Anfragen löschen',
            ],
        ],

        'DEALER_REQUEST.DELETE_REQUESTS.DESCRIPTION' => [
            'default' => 'Deleting selected requests will permanently remove them from your Customer Requests.',
            'locales' => [
                'en_GB' => 'Deleting selected requests will permanently remove them from your Customer Requests.',
                'de_DE' => 'Durch das Löschen ausgewählter Anfragen werden diese dauerhaft aus Ihren Kundenanfragen entfernt.',
            ],
        ],

        'DEALER_REQUEST.DELETE_REQUESTS.YES' => [
            'default' => 'Yes, delete requests',
            'locales' => [
                'en_GB' => 'Yes, delete requests',
                'de_DE' => 'Ja, Anfragen löschen',
            ],
        ],

        'DEALER_REQUEST.DELETE_REQUESTS.NO' => [
            'default' => 'No, keep requests',
            'locales' => [
                'en_GB' => 'No, keep requests',
                'de_DE' => 'Nein, Anfragen behalten',
            ],
        ],

        'DEALER_REQUEST.DELETE_REQUESTS.SUCCESS' => [
            'default' => 'Selected Requests were deleted successfully.',
            'locales' => [
                'en_GB' => 'Selected Requests were deleted successfully.',
                'de_DE' => 'Ausgewählte Anfragen wurden erfolgreich gelöscht.',
            ],
        ],

        'DEALER_REQUEST.ARCHIVE_REQUESTS.TITLE' => [
            'default' => 'Archive requests',
            'locales' => [
                'en_GB' => 'Archive requests',
                'de_DE' => 'Anfragen archivieren',
            ],
        ],

        'DEALER_REQUEST.ARCHIVE_REQUESTS.DESCRIPTION' => [
            'default' => 'Are you sure you want to archive these requests?<br>You can restore archived requests from the archive session.',
            'locales' => [
                'en_GB' => 'Are you sure you want to archive these requests?<br>You can restore archived requests from the archive session.',
                'de_DE' => 'Möchten Sie diese Anfragen wirklich archivieren?<br>Sie können archivierte Anfragen aus der Archivierungssitzung wiederherstellen.',
            ],
        ],

        'DEALER_REQUEST.ARCHIVE_REQUESTS.YES' => [
            'default' => 'Yes, archive requests',
            'locales' => [
                'en_GB' => 'Yes, archive requests',
                'de_DE' => 'Ja, Archivanfragen',
            ],
        ],

        'DEALER_REQUEST.ARCHIVE_REQUESTS.NO' => [
            'default' => 'No, keep requests active',
            'locales' => [
                'en_GB' => 'No, keep requests active',
                'de_DE' => 'Nein, Anfragen aktiv halten',
            ],
        ],

        'DEALER_REQUEST.ARCHIVE_REQUESTS.SUCCESS.PREFIX' => [
            'default' => 'Selected Requests were achieved successfully. You can find them',
            'locales' => [
                'en_GB' => 'Selected Requests were achieved successfully. You can find them',
                'de_DE' => 'Ausgewählte Anfragen wurden erfolgreich erfüllt. Sie können sie finden',
            ],
        ],

        'DEALER_REQUEST.ARCHIVE_REQUESTS.SUCCESS.ARCHIVED_REQUESTS_LINK' => [
            'default' => 'here',
            'locales' => [
                'en_GB' => 'here',
                'de_DE' => 'hier',
            ],
        ],

        'DEALER_REQUEST.ARCHIVE_REQUESTS.SUCCESS.POSTFIX' => [
            'default' => '.',
            'locales' => [
                'en_GB' => '.',
                'de_DE' => '.',
            ],
        ],

        'DEALER_REQUEST.RESTORE_REQUESTS.TITLE' => [
            'default' => 'Restore requests',
            'locales' => [
                'en_GB' => 'Restore requests',
                'de_DE' => 'Anfragen wiederherstellen',
            ],
        ],

        'DEALER_REQUEST.RESTORE_REQUESTS.DESCRIPTION' => [
            'default' => 'Are you sure you want to restore selected requests?',
            'locales' => [
                'en_GB' => 'Are you sure you want to restore selected requests?',
                'de_DE' => 'Möchten Sie die ausgewählten Anfragen wirklich wiederherstellen?',
            ],
        ],

        'DEALER_REQUEST.RESTORE_REQUESTS.YES' => [
            'default' => 'Yes, restore requests',
            'locales' => [
                'en_GB' => 'Yes, restore requests',
                'de_DE' => 'Ja, Anforderungen wiederherstellen',
            ],
        ],

        'DEALER_REQUEST.RESTORE_REQUESTS.NO' => [
            'default' => 'No, keep requests archived',
            'locales' => [
                'en_GB' => 'No, keep requests archived',
                'de_DE' => 'Nein, Anfragen archivieren',
            ],
        ],

        'DEALER_REQUEST.RESTORE_REQUESTS.SUCCESS.PREFIX' => [
            'default' => 'Selected Requests were restore successfully. You can find them',
            'locales' => [
                'en_GB' => 'Selected Requests were restore successfully. You can find them',
                'de_DE' => 'Ausgewählte Anfragen wurden erfolgreich wiederhergestellt. Sie können sie finden',
            ],
        ],

        'DEALER_REQUEST.RESTORE_REQUESTS.SUCCESS.REQUESTS_LINK' => [
            'default' => 'here',
            'locales' => [
                'en_GB' => 'here',
                'de_DE' => 'hier',
            ],
        ],

        'DEALER_REQUEST.RESTORE_REQUESTS.SUCCESS.POSTFIX' => [
            'default' => '.',
            'locales' => [
                'en_GB' => '.',
                'de_DE' => '.',
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
