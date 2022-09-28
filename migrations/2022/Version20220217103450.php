<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220217103450 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $messages = [
        'DEALER_REQUEST.FORM.BUTTON.OPEN' => [
            'default' => 'Contact Somfy Dealer',
            'locales' => [
                'en_GB' => 'Contact Somfy Dealer',
                'de_DE' => 'Somfy Fachhändler kontaktieren',
            ],
        ],

        'DEALER_REQUEST.FORM.BUTTON.SUBMIT' => [
            'default' => 'Send Your Request',
            'locales' => [
                'en_GB' => 'Send Your Request',
                'de_DE' => 'Senden Sie Ihre Anfrage',
            ],
        ],

        'DEALER_REQUEST.FORM.BUTTON.CLOSE' => [
            'default' => 'Close',
            'locales' => [
                'en_GB' => 'Close',
                'de_DE' => 'Schließen',
            ],
        ],

        'DEALER_REQUEST.FORM.SUCCESS' => [
            'default' => 'Your request has been successfully sent.',
            'locales' => [
                'en_GB' => 'Your request has been successfully sent.',
                'de_DE' => 'Ihre Anfrage wurde erfolgreich versendet.',
            ],
        ],

        'DEALER_REQUEST.MESSAGE.DONE' => [
            'default' => 'Your request has been successfully sent.',
            'locales' => [
                'en_GB' => 'Your request has been successfully sent.',
                'de_DE' => 'Ihre Anfrage wurde erfolgreich versendet.',
            ],
        ],

        'DEALER_REQUEST.MESSAGE.DONE_AND_COPY' => [
            'default' => 'Your request has been successfully sent. You can find a copy of it inside your email inbox.',
            'locales' => [
                'en_GB' => 'Your request has been successfully sent. You can find a copy of it inside your email inbox.',
                'de_DE' => 'Ihre Anfrage wurde erfolgreich versendet. Sie finden eine Kopie davon in Ihrem E-Mail-Posteingang.',
            ],
        ],

        'DEALER_REQUEST.FORM.TITLE' => [
            'default' => 'Contact Somfy Dealer',
            'locales' => [
                'en_GB' => 'Contact Somfy Dealer',
                'de_DE' => 'Somfy Fachhändler Kontaktieren',
            ],
        ],

        'DEALER_REQUEST.FORM.SUB_TITLE' => [
            'default' => 'Share your wishlist with your professional specialist company and receive an individual offer.',
            'locales' => [
                'en_GB' => 'Share your wishlist with your professional specialist company and receive an individual offer.',
                'de_DE' => 'Teilen Sie Ihre Merkliste mit Ihrem professionellen Fachbetrieb und erhalten Sie ein individuelles Angebot.',
            ],
        ],

        'DEALER_REQUEST.FORM.LABEL.NAME' => [
            'default' => 'Name',
            'locales' => [
                'en_GB' => 'Name',
                'de_DE' => 'Name',
            ],
        ],

        'DEALER_REQUEST.FORM.LABEL.EMAIL' => [
            'default' => 'E-Mail Address',
            'locales' => [
                'en_GB' => 'E-Mail Address',
                'de_DE' => 'E-Mail Adresse',
            ],
        ],

        'DEALER_REQUEST.FORM.LABEL.PHONE' => [
            'default' => 'Phone (optional)',
            'locales' => [
                'en_GB' => 'Phone (optional)',
                'de_DE' => 'Telefon (optional)',
            ],
        ],

        'DEALER_REQUEST.FORM.LABEL.ADDRESS' => [
            'default' => 'Address (optional)',
            'locales' => [
                'en_GB' => 'Address (optional)',
                'de_DE' => 'Adresse (optional)',
            ],
        ],

        'DEALER_REQUEST.FORM.LABEL.MESSAGE' => [
            'default' => 'What is your request?',
            'locales' => [
                'en_GB' => 'What is your request?',
                'de_DE' => 'Was ist Deine Anfrage?',
            ],
        ],

        'DEALER_REQUEST.FORM.MESSAGE_PLACEHOLDER' => [
            'default' => 'Write a message for the expert here.',
            'locales' => [
                'en_GB' => 'Write a message for the expert here.',
                'de_DE' => 'Schreiben Sie hier eine Nachricht an den Experten.',
            ],
        ],

        'DEALER_REQUEST.FORM.LABEL.SEND_COPY' => [
            'default' => 'Send a copy to my email.',
            'locales' => [
                'en_GB' => 'Send a copy to my email.',
                'de_DE' => 'Senden Sie eine Kopie an meine E-Mail.',
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
