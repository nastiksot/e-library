<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210925131708 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE `product_sets`
                ADD `recommended` TINYINT(1) DEFAULT 0 NOT NULL,
                ADD `layer1_icon_x` INT DEFAULT NULL,
                ADD `layer1_icon_y` INT DEFAULT NULL,
                ADD `layer1_active` TINYINT(1) DEFAULT 0 NOT NULL,
                ADD `layer2_icon_x` INT DEFAULT NULL,
                ADD `layer2_icon_y` INT DEFAULT NULL,
                ADD `layer2_active` TINYINT(1) DEFAULT 0 NOT NULL,
                ADD `layer3_icon_x` INT DEFAULT NULL,
                ADD `layer3_icon_y` INT DEFAULT NULL,
                ADD `layer3_active` TINYINT(1) DEFAULT 0 NOT NULL
        ');

        $this->addSql('
            ALTER TABLE `product_set_translations`
                ADD `icon_title` VARCHAR(255) DEFAULT NULL
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE `product_sets`
                DROP `recommended`,
                DROP `layer1_icon_x`,
                DROP `layer1_icon_y`,
                DROP `layer1_active`,
                DROP `layer2_icon_x`,
                DROP `layer2_icon_y`,
                DROP `layer2_active`,
                DROP `layer3_icon_x`,
                DROP `layer3_icon_y`,
                DROP `layer3_active`
        ');

        $this->addSql('
            ALTER TABLE `product_set_translations`
                DROP `icon_title`
        ');
    }
}
