<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220127104514 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // changes in SLIDERS table
        $this->addSql("ALTER TABLE `sliders` CHANGE `image` `file` VARCHAR(255) NULL");
        $this->addSql("
            ALTER TABLE `sliders`
                ADD `file_mime_type` VARCHAR(255) NULL
                    COMMENT '(DC2Type:App\\\\Contracts\\\\Dictionary\\\\SliderFileMimeType)'
                    AFTER `file`
        ");
        $this->addSql("ALTER TABLE `sliders` CHANGE `image_mobile` `file_mobile` VARCHAR(255) NULL");
        $this->addSql("
            ALTER TABLE `sliders`
                ADD `file_mobile_mime_type` VARCHAR(255) NULL
                    COMMENT '(DC2Type:App\\\\Contracts\\\\Dictionary\\\\SliderFileMimeType)'
                    AFTER `file_mobile`
        ");
        $this->addSql("UPDATE `sliders` SET `file_mime_type`='image'");
        $this->addSql("UPDATE `sliders` SET `file_mobile_mime_type`='image'");


        // changes in SLIDES table
        $this->addSql("ALTER TABLE `slides` CHANGE `image` `file` VARCHAR(255) NULL");
        $this->addSql("
            ALTER TABLE `slides`
                ADD `file_mime_type` VARCHAR(255) NULL
                    COMMENT '(DC2Type:App\\\\Contracts\\\\Dictionary\\\\SliderFileMimeType)'
                    AFTER `file`
        ");
        $this->addSql("ALTER TABLE `slides` CHANGE `image_mobile` `file_mobile` VARCHAR(255) NULL");
        $this->addSql("
            ALTER TABLE `slides`
                ADD `file_mobile_mime_type` VARCHAR(255) NULL
                    COMMENT '(DC2Type:App\\\\Contracts\\\\Dictionary\\\\SliderFileMimeType)'
                    AFTER `file_mobile`
        ");
        $this->addSql("UPDATE `slides` SET `file_mime_type`='image'");
        $this->addSql("UPDATE `slides` SET `file_mobile_mime_type`='image'");
    }

    public function down(Schema $schema): void
    {
        // changes in SLIDERS table
        $this->addSql("ALTER TABLE `sliders` CHANGE `file` `image` VARCHAR(255) NULL");
        $this->addSql("ALTER TABLE `sliders` DROP `file_mime_type`");
        $this->addSql("ALTER TABLE `sliders` CHANGE `file_mobile` `image_mobile` VARCHAR(255) NULL");
        $this->addSql("ALTER TABLE `sliders` DROP `file_mobile_mime_type`");


        // changes in SLIDES table
        $this->addSql("ALTER TABLE `slides` CHANGE `file` `image` VARCHAR(255) NULL");
        $this->addSql("ALTER TABLE `slides` DROP `file_mime_type`");
        $this->addSql("ALTER TABLE `slides` CHANGE `file_mobile` `image_mobile` VARCHAR(255) NULL");
        $this->addSql("ALTER TABLE `slides` DROP `file_mobile_mime_type`");
    }
}
