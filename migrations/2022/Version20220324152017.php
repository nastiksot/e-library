<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220324152017 extends AbstractMigration
{

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE mail_template_translations ADD subject2 VARCHAR(255) DEFAULT NULL;');
        $this->addSql('ALTER TABLE mail_template_translations ADD content3 LONGTEXT DEFAULT NULL;');


        $this->addSql("
            UPDATE `mail_template_translations` AS t1
                INNER JOIN `mail_templates` t2 ON t1.translatable_id = t2.id AND t2.type='dealer-request'
                SET
                    t1.`subject2`=t1.`subject`,
                    t1.content3=t1.`content`
                WHERE t1.`locale`='de_DE';
            ");
        $this->addSql("
            UPDATE `mail_template_translations` AS t1
                INNER JOIN `mail_templates` t2 ON t1.translatable_id = t2.id AND t2.type='dealer-request'
                SET
                    t1.`subject2`=t1.`subject`,
                    t1.content3=t1.`content`
                WHERE t1.`locale`='en_GB';
            ");


        $this->addSql("
            UPDATE `mail_template_translations` AS t1
                INNER JOIN `mail_templates` t2 ON t1.translatable_id = t2.id AND t2.type='dealer-request'
                SET
                    t1.`subject`='Sie haben eine neue Anfrage erhalten',
                    t1.content='<p style=\"color:#3c4f64; font-size:22px; font-weight:100\">" .
                        "Sie haben eine neue Anfrage erhalten!" .
                        "</p>" .
                        "<p style=\"color:#485c74; font-size:16px; line-height:22px\">" .
                            "Hallo %%dealer_name%%,<br>" .
                            "%%customer_name%% hat Ihnen eine Anfrage gesendet." .
                        "</p>'
                WHERE t1.`locale`='de_DE';
            ");
        $this->addSql("
            UPDATE `mail_template_translations` AS t1
                INNER JOIN `mail_templates` t2 ON t1.translatable_id = t2.id AND t2.type='dealer-request'
                SET
                    t1.`subject`='You received a new request',
                    t1.content='<p style=\"color:#3c4f64; font-size:22px; font-weight:100\">You received a new request!</p>" .
                                "<p style=\"color:#485c74; font-size:16px; line-height:22px\">" .
                                    "Hey %%dealer_name%%,<br>" .
                                    "%%customer_name%% sent you a request." .
                                "</p>'
                WHERE t1.`locale`='en_GB';
            ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("
            UPDATE `mail_template_translations` AS t1
                INNER JOIN `mail_templates` t2 ON t1.translatable_id = t2.id AND t2.type='dealer-request'
                SET
                    t1.`subject`=t1.`subject2`,
                    t1.content=t1.`content3`
                WHERE t1.`locale`='de_DE';
            ");
        $this->addSql("
            UPDATE `mail_template_translations` AS t1
                INNER JOIN `mail_templates` t2 ON t1.translatable_id = t2.id AND t2.type='dealer-request'
                SET
                    t1.`subject`=t1.`subject2`,
                    t1.content=t1.`content3`
                WHERE t1.`locale`='en_GB';
            ");


        $this->addSql('ALTER TABLE mail_template_translations DROP content3;');
        $this->addSql('ALTER TABLE mail_template_translations DROP subject2;');
    }
}
