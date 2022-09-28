<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220415070822 extends AbstractMigration
{

    public function up(Schema $schema): void
    {
        $this->addSql('UPDATE product_sets SET icon= "wind-icon" WHERE icon="warning-icon";');

        $this->addSql('UPDATE product_sets SET icon= "thermometer-icon" WHERE icon="key-icon";');

        $this->addSql('UPDATE product_sets SET icon= "key-icon" WHERE icon="tipp-icon";');

        $this->addSql('UPDATE product_sets SET icon= "moon-icon" WHERE icon="sleep-icon";');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('UPDATE product_sets SET icon= "warning-icon" WHERE icon="wind-icon";');

        $this->addSql('UPDATE product_sets SET icon= "tipp-icon" WHERE icon="key-icon";');

        $this->addSql('UPDATE product_sets SET icon= "key-icon" WHERE icon="thermometer-icon";');

        $this->addSql('UPDATE product_sets SET icon= "sleep-icon" WHERE icon="moon-icon";');
    }
}
