<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220326205105 extends AbstractMigration
{
    private $types   = ['register', 'register-notification', 'forgot-password',];
    private $locales = ['en_GB', 'de_DE',];

    public function up(Schema $schema): void
    {
        $this->addSql("UPDATE `messages` SET `code` = 'USER.REGISTER.SAVE_CONF.TITLE' WHERE`code`='USER.REGISTER.TITLE';");
        $this->addSql("UPDATE `messages` SET `code` = 'USER.REGISTER.SAVE_CONF.SUB_TITLE' WHERE`code`='USER.REGISTER.SUB_TITLE';");
        $this->addSql("UPDATE `messages` SET `code` = 'USER.REGISTER.SAVE_CONF.DONE' WHERE`code`='USER.REGISTER.DONE';");

        foreach ($this->types as $type) {
            foreach ($this->locales as $locale) {
                $this->addSql("
            UPDATE `mail_template_translations` AS t1
                INNER JOIN `mail_templates` t2 ON t1.translatable_id = t2.id AND t2.type='{$type}'
                SET
                    t1.`subject`=t1.`subject2`,
                    t1.content=t1.`content3`
                WHERE t1.`locale`='{$locale}';
            ");

                $this->addSql("
            UPDATE `mail_template_translations` AS t1
                INNER JOIN `mail_templates` t2 ON t1.translatable_id = t2.id AND t2.type='{$type}'
                SET
                    t1.`subject2`=NULL,
                    t1.`content3`=NULL
                WHERE t1.`locale`='{$locale}';
            ");
            }
        }

        foreach ($this->locales as $locale) {
            $this->addSql("
            UPDATE `mail_template_translations` AS t1
                INNER JOIN `mail_templates` t2 ON t1.translatable_id = t2.id AND t2.type='register'
                SET t1.content2=t1.`content`
                WHERE t1.`locale`='{$locale}';
            ");
        }


        $this->addSql("
            UPDATE `mail_template_translations` AS t1
                INNER JOIN `mail_templates` t2 ON t1.translatable_id = t2.id AND t2.type='register'
                SET
                    t1.content='Hello.<br />" .
                    "<br />" .
                    "Please click on this <a href=\"%%confirm_register_link%%\" style=\"color: #fcac22;\">link</a> " .
                    "to confirm your email.<br />" .
                    "<br />" .
                    "Thanks very much.<br />" .
                    "Your Somfy private customer service!'
                WHERE t1.`locale`='en_GB';
            ");
        $this->addSql("
            UPDATE `mail_template_translations` AS t1
                INNER JOIN `mail_templates` t2 ON t1.translatable_id = t2.id AND t2.type='register'
                SET
                    t1.content='Hallo.<br />" .
                    "<br />" .
                    "Bitte klicken Sie auf diesen <a href=\"%%confirm_register_link%%\" style=\"color: #fcac22;\">Link</a>, " .
                    "um Ihre E-Mail zu bestätigen.<br />" .
                    "<br />" .
                    "Vielen Dank.<br />" .
                    "Ihr Somfy Privatkunden - Service!'
                WHERE t1.`locale`='de_DE';
            ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM `messages` WHERE `code` IN ('USER.REGISTER.TITLE', 'USER.REGISTER.SUB_TITLE', 'USER.REGISTER.DONE');");
        $this->addSql("UPDATE `messages` SET `code` = 'USER.REGISTER.TITLE' WHERE`code`='USER.REGISTER.SAVE_CONF.TITLE';");
        $this->addSql("UPDATE `messages` SET `code` = 'USER.REGISTER.SUB_TITLE' WHERE`code`='USER.REGISTER.SAVE_CONF.SUB_TITLE';");
        $this->addSql("UPDATE `messages` SET `code` = 'USER.REGISTER.DONE' WHERE`code`='USER.REGISTER.SAVE_CONF.DONE';");


        foreach ($this->types as $type) {
            foreach ($this->locales as $locale) {
                $this->addSql("
            UPDATE `mail_template_translations` AS t1
                INNER JOIN `mail_templates` t2 ON t1.translatable_id = t2.id AND t2.type='{$type}'
                SET
                    t1.`subject`=t1.`subject2`,
                    t1.content=t1.`content3`
                WHERE t1.`locale`='{$locale}';
            ");
            }
        }


        foreach ($this->locales as $locale) {
            $this->addSql("
            UPDATE `mail_template_translations` AS t1
                INNER JOIN `mail_templates` t2 ON t1.translatable_id = t2.id AND t2.type='register'
                SET t1.content=t1.`content2`
                WHERE t1.`locale`='{$locale}';
            ");
            $this->addSql("
            UPDATE `mail_template_translations` AS t1
                INNER JOIN `mail_templates` t2 ON t1.translatable_id = t2.id AND t2.type='register'
                SET t1.content2=NULL
                WHERE t1.`locale`='{$locale}';
            ");
        }
    }
}
