<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220310163822 extends AbstractMigration
{

    public function up(Schema $schema): void
    {
        $this->addSql('
            UPDATE messages
                SET `code` = "DEALER_REQUEST.RESTORE_REQUEST.DESCRIPTION"
                WHERE `code` = "DEALER_REQUEST.RESTORE_REQUEST.CONFIRM"
        ');

        $this->addSql('
            UPDATE messages
                SET `code` = "DEALER_REQUEST.ACTION.DELETE_REQUEST"
                WHERE `code` = "DEALER_REQUEST.DELETE_ARCHIVE"
        ');

        $this->addSql('
            UPDATE messages
                SET `code` = "DEALER_REQUEST.ARCHIVE_REQUESTS_CATEGORY"
                WHERE `code` = "DEALER_REQUEST.ARCHIVE_REQUESTS"
        ');

        $this->addSql("
            UPDATE `messages`
                SET `default_value` = REPLACE(`default_value`,'customer inquiries','Customer Requests')
                WHERE `code`='DEALER_REQUEST.DELETE_REQUEST.DESCRIPTION';
            ");

        $this->addSql("
            UPDATE `message_translations` AS t1
                LEFT JOIN `messages` t2 ON t1.translatable_id = t2.id AND `locale`='en_GB'
                SET t1.`value` = REPLACE(`value`,'customer inquiries','Customer Requests')
                WHERE t2.`code`='DEALER_REQUEST.DELETE_REQUEST.DESCRIPTION'
            ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('
            UPDATE messages
                SET `code` = "DEALER_REQUEST.RESTORE_REQUEST.CONFIRM"
                WHERE `code` = "DEALER_REQUEST.RESTORE_REQUEST.DESCRIPTION"
        ');
        $this->addSql('
            UPDATE messages
                SET `code` = "DEALER_REQUEST.DELETE_ARCHIVE"
                WHERE `code` = "DEALER_REQUEST.ACTION.DELETE_REQUEST"
        ');
        $this->addSql('
            UPDATE messages
                SET `code` = "DEALER_REQUEST.ARCHIVE_REQUESTS"
                WHERE `code` = "DEALER_REQUEST.ARCHIVE_REQUESTS_CATEGORY"
        ');

        $this->addSql("
            UPDATE `messages`
                SET `default_value` = REPLACE(`default_value`,'Customer Requests','customer inquiries')
                WHERE `code`='DEALER_REQUEST.DELETE_REQUEST.DESCRIPTION';
            ");

        $this->addSql("
            UPDATE `message_translations` AS t1
                LEFT JOIN `messages` t2 ON t1.translatable_id = t2.id AND `locale`='en_GB'
                SET t1.`value` = REPLACE(`value`,'Customer Requests','customer inquiries')
                WHERE t2.`code`='DEALER_REQUEST.DELETE_REQUEST.DESCRIPTION'
            ");
    }
}
