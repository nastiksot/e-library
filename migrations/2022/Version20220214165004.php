<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\Uid\UuidV4;

final class Version20220214165004 extends AbstractMigration
{

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE dealers ADD uid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');

        $all = $this->connection->executeQuery("SELECT * FROM dealers")->fetchAllAssociative();
        foreach ($all as $dealer) {
            $this->addSql(
                'UPDATE dealers SET uid = :uid WHERE id = :id',
                [
                    'id'  => $dealer['id'],
                    'uid' => (new UuidV4)->toBinary(),
                ]
            );
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE dealers DROP uid');
    }
}
