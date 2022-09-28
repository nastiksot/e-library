<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211221173531 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `sliders` ADD `image_mobile` VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE `slides` ADD `image_mobile` VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `sliders` DROP `image_mobile`');
        $this->addSql('ALTER TABLE `slides` DROP `image_mobile`');
    }
}
