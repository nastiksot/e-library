<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220218081405 extends AbstractMigration
{

    use MessagesMigrationTrait;

    private array $messages = [
        'DEALER_REQUEST.TITLE' => [
            'default' => 'Customer Requests',
            'locales' => [
                'en_GB' => 'Customer Requests',
                'de_DE' => 'Kundenanfragen',
            ],
        ],

        'DEALER_REQUEST.FILTER_TITLE' => [
            'default' => 'Show:',
            'locales' => [
                'en_GB' => 'Show:',
                'de_DE' => 'Show:',
            ],
        ],

        'DEALER_REQUEST.STATUS.NEW' => [
            'default' => 'New',
            'locales' => [
                'en_GB' => 'New',
                'de_DE' => 'Neu',
            ],
        ],

        'DEALER_REQUEST.STATUS.ANSWERED' => [
            'default' => 'Answered',
            'locales' => [
                'en_GB' => 'Answered',
                'de_DE' => 'Antwortete',
            ],
        ],

        'DEALER_REQUEST.STATUS.MEETING_PLANNED' => [
            'default' => 'Meeting planned',
            'locales' => [
                'en_GB' => 'Meeting planned',
                'de_DE' => 'Treffen geplant',
            ],
        ],

        'DEALER_REQUEST.STATUS.CLOSED' => [
            'default' => 'Closed',
            'locales' => [
                'en_GB' => 'Closed',
                'de_DE' => 'Geschlossen',
            ],
        ],

        'DEALER_REQUEST.CONTACT_NAME' => [
            'default' => 'Client Name',
            'locales' => [
                'en_GB' => 'Client Name',
                'de_DE' => 'Client Name',
            ],
        ],

        'DEALER_REQUEST.TOPIC' => [
            'default' => 'Topic',
            'locales' => [
                'en_GB' => 'Topic',
                'de_DE' => 'Thema',
            ],
        ],

        'DEALER_REQUEST.DATE_RECEIVED' => [
            'default' => 'Date Received',
            'locales' => [
                'en_GB' => 'Date Received',
                'de_DE' => 'Empfangsdatum',
            ],
        ],

        'DEALER_REQUEST.DATE_OF_REVIEW' => [
            'default' => 'Date Of Review',
            'locales' => [
                'en_GB' => 'Date Of Review',
                'de_DE' => 'Datum der Überprüfung',
            ],
        ],

        'DEALER_REQUEST.REQUEST_STATUS' => [
            'default' => 'Request Status',
            'locales' => [
                'en_GB' => 'Request Status',
                'de_DE' => 'Anforderungsstatus',
            ],
        ],

        'DEALER_REQUEST.ARCHIVE_REQUESTS' => [
            'default' => 'Archive requests',
            'locales' => [
                'en_GB' => 'Archive requests',
                'de_DE' => 'Anfragen archivieren',
            ],
        ],

        'DEALER_REQUEST.DELETE_ARCHIVE' => [
            'default' => 'Delete request',
            'locales' => [
                'en_GB' => 'Delete request',
                'de_DE' => 'Anfrage löschen',
            ],
        ],

        'DEALER_REQUEST.PHONE' => [
            'default' => 'Phone',
            'locales' => [
                'en_GB' => 'Phone',
                'de_DE' => 'Telefon',
            ],
        ],

        'DEALER_REQUEST.EMAIL' => [
            'default' => 'Email Address',
            'locales' => [
                'en_GB' => 'Email Address',
                'de_DE' => 'E-Mail',
            ],
        ],

        'DEALER_REQUEST.ADDRESS' => [
            'default' => 'Address',
            'locales' => [
                'en_GB' => 'Address',
                'de_DE' => 'Adresse',
            ],
        ],

        'DEALER_REQUEST.LINK_TO_CONFIGURATION' => [
            'default' => 'Link To The Configuration',
            'locales' => [
                'en_GB' => 'Link To The Configuration',
                'de_DE' => 'Link zur Konfiguration',
            ],
        ],

        'DEALER_REQUEST.EXPERT_COMMENT.TITLE' => [
            'default' => 'Expert comment (optional)',
            'locales' => [
                'en_GB' => 'Expert comment (optional)',
                'de_DE' => 'Expertenkommentar (optional)',
            ],
        ],

        'DEALER_REQUEST.EXPERT_COMMENT.PLACEHOLDER' => [
            'default' => 'Add a comment about this request',
            'locales' => [
                'en_GB' => 'Add a comment about this request',
                'de_DE' => 'Fügen Sie einen Kommentar zu dieser Anfrage hinzu',
            ],
        ],

        'DEALER_REQUEST.EXPERT_COMMENT.SAVE' => [
            'default' => 'Save',
            'locales' => [
                'en_GB' => 'Save',
                'de_DE' => 'Speichern',
            ],
        ],

        'DEALER_REQUEST.EXPERT_COMMENT.CANCEL' => [
            'default' => 'Cancel',
            'locales' => [
                'en_GB' => 'Cancel',
                'de_DE' => 'Abbrechen',
            ],
        ],

        'DEALER_REQUEST.EXPERT_COMMENT.EDIT' => [
            'default' => 'Edit',
            'locales' => [
                'en_GB' => 'Edit',
                'de_DE' => 'Bearbeiten',
            ],
        ],

        'DEALER_REQUEST.EXPERT_COMMENT.DELETE' => [
            'default' => 'Delete',
            'locales' => [
                'en_GB' => 'Delete',
                'de_DE' => 'Löschen',
            ],
        ],

        'DEALER_REQUEST.DELETE_REQUEST.TITLE' => [
            'default' => 'Delete request',
            'locales' => [
                'en_GB' => 'Delete request',
                'de_DE' => 'Anfrage löschen',
            ],
        ],

        'DEALER_REQUEST.DELETE_REQUEST.DESCRIPTION' => [
            'default' => 'Deleting a request will permanently remove it from your customer inquiries.',
            'locales' => [
                'en_GB' => 'Deleting a request will permanently remove it from your customer inquiries.',
                'de_DE' => 'Durch das Löschen einer Anfrage wird diese dauerhaft aus Ihren Kundenanfragen entfernt.',
            ],
        ],

        'DEALER_REQUEST.DELETE_REQUEST.YES' => [
            'default' => 'Yes, delete request',
            'locales' => [
                'en_GB' => 'Yes, delete request',
                'de_DE' => 'Ja, Anfrage löschen',
            ],
        ],

        'DEALER_REQUEST.DELETE_REQUEST.NO' => [
            'default' => 'No, keep request',
            'locales' => [
                'en_GB' => 'No, keep request',
                'de_DE' => 'Nein, Anfrage behalten',
            ],
        ],

        'DEALER_REQUEST.DELETE_REQUEST.SUCCESS' => [
            'default' => "'s request was deleted successfully.",
            'locales' => [
                'en_GB' => "'s request was deleted successfully.",
                'de_DE' => 's Anfrage wurde erfolgreich gelöscht.',
            ],
        ],

        'DEALER_REQUEST.ARCHIVE_REQUEST.TITLE' => [
            'default' => 'Archive request',
            'locales' => [
                'en_GB' => 'Archive request',
                'de_DE' => 'Anfrage archivieren',
            ],
        ],

        'DEALER_REQUEST.ARCHIVE_REQUEST.DESCRIPTION' => [
            'default' => 'Are you sure you want to archive this request?<br>You can restore archived request from the archive session.',
            'locales' => [
                'en_GB' => 'Are you sure you want to archive this request?<br>You can restore archived request from the archive session.',
                'de_DE' => 'Möchten Sie diese Anfrage wirklich archivieren?<br>Sie können die archivierte Anfrage aus der Archivierungssitzung wiederherstellen.',
            ],
        ],

        'DEALER_REQUEST.ARCHIVE_REQUEST.YES' => [
            'default' => 'Yes, archive request',
            'locales' => [
                'en_GB' => 'Yes, archive request',
                'de_DE' => 'Ja, Archivanfrage',
            ],
        ],

        'DEALER_REQUEST.ARCHIVE_REQUEST.NO' => [
            'default' => 'No, keep request active',
            'locales' => [
                'en_GB' => 'No, keep request active',
                'de_DE' => 'Nein, Anfrage aktiv halten',
            ],
        ],

        'DEALER_REQUEST.ARCHIVE_REQUEST.SUCCESS.PREFIX' => [
            'default' => "'s request was achieved successfully. You can find it",
            'locales' => [
                'en_GB' => "'s request was achieved successfully. You can find it",
                'de_DE' => 's Anfrage wurde erfolgreich erfüllt. Du kannst es finden',
            ],
        ],

        'DEALER_REQUEST.ARCHIVE_REQUEST.SUCCESS.ARCHIVED_REQUESTS_LINK' => [
            'default' => 'here',
            'locales' => [
                'en_GB' => 'here',
                'de_DE' => 'hier',
            ],
        ],

        'DEALER_REQUEST.ARCHIVE_REQUEST.SUCCESS.POSTFIX' => [
            'default' => '.',
            'locales' => [
                'en_GB' => '.',
                'de_DE' => '.',
            ],
        ],

        'DEALER_REQUEST.RESTORE_REQUEST.TITLE' => [
            'default' => 'Restore request',
            'locales' => [
                'en_GB' => 'Restore request',
                'de_DE' => 'Anforderung wiederherstellen',
            ],
        ],

        'DEALER_REQUEST.RESTORE_REQUEST.CONFIRM' => [
            'default' => 'Are you sure you want to restore this request?',
            'locales' => [
                'en_GB' => 'Are you sure you want to restore this request?',
                'de_DE' => 'Möchten Sie diese Anfrage wirklich wiederherstellen?',
            ],
        ],

        'DEALER_REQUEST.RESTORE_REQUEST.YES' => [
            'default' => 'Yes, restore request',
            'locales' => [
                'en_GB' => 'Yes, restore request',
                'de_DE' => 'Ja, Anfrage wiederherstellen',
            ],
        ],

        'DEALER_REQUEST.RESTORE_REQUEST.NO' => [
            'default' => 'No, keep request archived',
            'locales' => [
                'en_GB' => 'No, keep request archived',
                'de_DE' => 'Nein, Anfrage archivieren',
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
