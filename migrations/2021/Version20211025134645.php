<?php

declare(strict_types=1);

namespace DoctrineMigrations;

require_once __DIR__ . '/../MessagesMigrationTrait.php';

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211025134645 extends AbstractMigration
{
    use MessagesMigrationTrait;

    private array $messages = [
        'GENERAL.REQUIRED_FIELDS' => [
            'default' => '* Required fields',
            'locales' => [
                'en-GB' => '* Required fields',
                'de-DE' => '* Pflichfelder',
            ],
        ],

        'USER.LABEL.FIRST_NAME' => [
            'default' => 'First Name',
            'locales' => [
                'en-GB' => 'First Name',
                'de-DE' => 'Vorname',
            ],
        ],

        'USER.LABEL.LAST_NAME' => [
            'default' => 'Last Name',
            'locales' => [
                'en-GB' => 'Last Name',
                'de-DE' => 'Nachname',
            ],
        ],

        'USER.LABEL.USERNAME' => [
            'default' => 'E-mail',
            'locales' => [
                'en-GB' => 'E-mail',
                'de-DE' => 'E-mail',
            ],
        ],

        ////////////////////////////////////////////////////////////////


        'USER.LABEL.EMAIL'            => [
            'default' => 'E-mail',
            'locales' => [
                'en-GB' => 'E-mail',
                'de-DE' => 'E-mail',
            ],
        ],
        'USER.LABEL.PASSWORD'         => [
            'default' => 'Password',
            'locales' => [
                'en-GB' => 'Password',
                'de-DE' => 'Passwort',
            ],
        ],
        'USER.LABEL.CONFIRM_PASSWORD' => [
            'default' => 'Confirm Password',
            'locales' => [
                'en-GB' => 'Confirm Password',
                'de-DE' => 'Passwort-Bestätigung',
            ],
        ],

        'USER.LABEL.ACCEPT_NEWS'                  => [
            'default' => 'I would like to receive the Somfy newsletter.',
            'locales' => [
                'en-GB' => 'I would like to receive the Somfy newsletter.',
                'de-DE' => 'Ich möchte den Somfy Newsletter erhalten.',
            ],
        ],
        'USER.LABEL.ACCEPT_PROCESS_PERSONAL_DATA' => [
            'default' => 'I consent to my data being saved and forwarded to third parties.',
            'locales' => [
                'en-GB' => 'I consent to my data being saved and forwarded to third parties.',
                'de-DE' => 'Ich bin damit einverstanden, dass meine Daten gespeichert und an Dritte weitergeleitet werden.',
            ],
        ],

        //////////////////////////////////////////////////////////////////////

        'USER.REGISTER.TITLE' => [
            'default' => 'Save configuration',
            'locales' => [
                'en-GB' => 'Save configuration',
                'de-DE' => 'Konfiguration speichern',
            ],
        ],

        'USER.REGISTER.SUB_TITLE' => [
            'default' => 'Please enter your email address to save the configuration.',
            'locales' => [
                'en-GB' => 'Please enter your email address to save the configuration.',
                'de-DE' => 'Zum Speichern der Konfiguration geben Sie bitte Ihre E-Mail-Adresse ein',
            ],
        ],

        'USER.REGISTER.DONE' => [
            'default' => 'Configuration saved',
            'locales' => [
                'en-GB' => 'Configuration saved',
                'de-DE' => 'Konfiguration gespeichert',
            ],
        ],

        'USER.REGISTER.MESSAGE.SUCCESS'             => [
            'default' => 'An email has been sent to you. Please check.',
            'locales' => [
                'en-GB' => 'An email has been sent to you. Please check.',
                'de-DE' => 'Eine E-Mail wurde an Sie gesendet. Bitte prüfen.',
            ],
        ],
        'USER.REGISTER.MESSAGE.LOGIN'               => [
            'default' => 'login',
            'locales' => [
                'en-GB' => 'login',
                'de-DE' => 'Einloggen',
            ],
        ],
        'USER.REGISTER.MESSAGE.DO_YOU_HAVE_ACCOUNT' => [
            'default' => 'Do you have an account?',
            'locales' => [
                'en-GB' => 'Do you have an account?',
                'de-DE' => 'Haben Sie Konto?',
            ],
        ],

        'USER.REGISTER.BUTTON.SUBMIT' => [
            'default' => 'Create a New Account',
            'locales' => [
                'en-GB' => 'Create a New Account',
                'de-DE' => 'Neues Konto anlegen',
            ],
        ],

        //////////////////////////////////////////////////////////////////////


        'USER.REGISTER_CONFIRM.TITLE' => [
            'default' => 'Almost there!',
            'locales' => [
                'en-GB' => 'Almost there!',
                'de-DE' => 'Fast geschafft!',
            ],
        ],

        'USER.REGISTER_CONFIRM.SUB_TITLE' => [
            'default' => 'In order to complete your free registration and to access your configurations later, assign a password:',
            'locales' => [
                'en-GB' => 'In order to complete your free registration and to access your configurations later, assign a password:',
                'de-DE' => 'Um Ihre kostenlose Registrierung abzuschließen und auch später auf Ihre Konfigurationen zuzugreifen, vergeben Sie nun ein Passwort:',
            ],
        ],

        'USER.REGISTER_CONFIRM.DONE' => [
            'default' => 'Registration completed',
            'locales' => [
                'en-GB' => 'Registration completed',
                'de-DE' => 'Registrierung vervollständigt',
            ],
        ],

        'USER.REGISTER_CONFIRM.MESSAGE.SUCCESS' => [
            'default' => 'You have successfully registered.',
            'locales' => [
                'en-GB' => 'You have successfully registered.',
                'de-DE' => 'Sie haben sich erfolgreich registriert.',
            ],
        ],

        'USER.REGISTER_CONFIRM.BUTTON.SUBMIT' => [
            'default' => 'Create a New Account',
            'locales' => [
                'en-GB' => 'Create a New Account',
                'de-DE' => 'Neues Konto anlegen',
            ],
        ],

        //////////////////////////////////////////////////////////////////////


        'USER.FORGOT_PASSWORD.TITLE' => [
            'default' => 'Forgot Password',
            'locales' => [
                'en-GB' => 'Forgot Password',
                'de-DE' => 'Passwort vergessen',
            ],
        ],

        'USER.FORGOT_PASSWORD.SUB_TITLE' => [
            'default' => 'To restore your password, fill out the form',
            'locales' => [
                'en-GB' => 'To restore your password, fill out the form',
                'de-DE' => 'Um Ihr Passwort wiederherzustellen, füllen Sie das Formular aus.',
            ],
        ],

        'USER.FORGOT_PASSWORD.DONE' => [
            'default' => 'Completed',
            'locales' => [
                'en-GB' => 'Completed',
                'de-DE' => 'Vollendet',
            ],
        ],

        'USER.FORGOT_PASSWORD.MESSAGE.SUCCESS' => [
            'default' => 'An email has been sent to you. Please check.',
            'locales' => [
                'en-GB' => 'An email has been sent to you. Please check.',
                'de-DE' => 'Eine E-Mail wurde an Sie gesendet. Bitte prüfen.',
            ],
        ],

        'USER.FORGOT_PASSWORD.BUTTON.SUBMIT' => [
            'default' => 'Submit',
            'locales' => [
                'en-GB' => 'Submit',
                'de-DE' => 'Fertigstellen',
            ],
        ],


        //////////////////////////////////////////////////////////////////////


        'USER.RESET_PASSWORD.TITLE' => [
            'default' => 'Reset Password',
            'locales' => [
                'en-GB' => 'Reset Password',
                'de-DE' => 'Passwort zurücksetzen',
            ],
            'en-GB'   => 'Reset Password',
            'de-DE'   => 'Passwort zurücksetzen',
        ],

        'USER.RESET_PASSWORD.SUB_TITLE' => [
            'default' => 'To reset the password, fill out the form',
            'locales' => [
                'en-GB' => 'To reset the password, fill out the form',
                'de-DE' => 'Um das Passwort zurückzusetzen, füllen Sie das Formular aus',
            ],
        ],

        'USER.RESET_PASSWORD.DONE' => [
            'default' => 'Reset password completed',
            'locales' => [
                'en-GB' => 'Reset password completed',
                'de-DE' => 'Passwort zurücksetzen abgeschlossen',
            ],
        ],

        'USER.RESET_PASSWORD.MESSAGE.SUCCESS' => [
            'default' => 'The new password was successfully set.',
            'locales' => [
                'en-GB' => 'The new password was successfully set.',
                'de-DE' => 'Das neue Passwort wurde erfolgreich gesetzt',
            ],
        ],

        'USER.RESET_PASSWORD.BUTTON.SUBMIT' => [
            'default' => 'Submit',
            'locales' => [
                'en-GB' => 'Submit',
                'de-DE' => 'Fertigstellen',
            ],
        ],


        //////////////////////////////////////////////////////////////////////


        'USER.LOGIN.TITLE' => [
            'default' => 'Login',
            'locales' => [
                'en-GB' => 'Login',
                'de-DE' => 'Anmeldung',
            ],
        ],

        'USER.LOGIN.SUB_TITLE' => [
            'default' => 'To login, fill out the form',
            'locales' => [
                'en-GB' => 'To login, fill out the form',
                'de-DE' => 'Um sich anzumelden, füllen Sie das Formular aus',
            ],
        ],

        'USER.LOGIN.DONE' => [
            'default' => 'Login completed',
            'locales' => [
                'en-GB' => 'Login completed',
                'de-DE' => 'Anmeldung abgeschlossen',
            ],
        ],

        'USER.LOGIN.MESSAGE.SUCCESS' => [
            'default' => 'You have successfully login.',
            'locales' => [
                'en-GB' => 'You have successfully login.',
                'de-DE' => 'Sie haben sich erfolgreich angemeldet.',
            ],
        ],

        'USER.LOGIN.MESSAGE.FORGOT_PASSWORD' => [
            'default' => 'Forgot Password?',
            'locales' => [
                'en-GB' => 'Forgot Password?',
                'de-DE' => 'Passwort vergessen?',
            ],
        ],

        'USER.LOGIN.BUTTON.SUBMIT' => [
            'default' => 'Login',
            'locales' => [
                'en-GB' => 'Login',
                'de-DE' => 'Anmeldung',
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
