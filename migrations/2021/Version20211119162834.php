<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211119162834 extends AbstractMigration
{

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE products ADD price_end DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE products ADD special_shop TINYINT(1) DEFAULT \'0\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE products DROP price_end');
        $this->addSql('ALTER TABLE products DROP special_shop');
    }
}
