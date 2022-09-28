<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220314143506 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql("
            UPDATE `message_translations` AS t1
                LEFT JOIN `messages` t2 ON t1.translatable_id = t2.id AND `locale`='de_DE'
                SET t1.`value` = REPLACE(`value`,'Video abspielen','Video')
                WHERE t2.`code`='NAVIGATION.TAB.PLAY_VIDEO'
            ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("
            UPDATE `message_translations` AS t1
                LEFT JOIN `messages` t2 ON t1.translatable_id = t2.id AND `locale`='de_DE'
                SET t1.`value` = REPLACE(`value`,'Video','Video abspielen')
                WHERE t2.`code`='NAVIGATION.TAB.PLAY_VIDEO'
            ");
    }
}
