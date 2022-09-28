<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220216075603 extends AbstractMigration
{

    public function up(Schema $schema): void
    {
        $this->addSql("UPDATE `users` SET `roles` = REPLACE(`roles`,'ROLE_EXPERT_ADMIN','ROLE_DEALER_ADMIN');");
        $this->addSql("UPDATE `users` SET `roles` = REPLACE(`roles`,'ROLE_EXPERT_EMPLOYEE','ROLE_DEALER_EMPLOYEE');");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("UPDATE `users` SET `roles` = REPLACE(`roles`,'ROLE_DEALER_ADMIN','ROLE_EXPERT_ADMIN');");
        $this->addSql("UPDATE `users` SET `roles` = REPLACE(`roles`,'ROLE_DEALER_EMPLOYEE','ROLE_EXPERT_EMPLOYEE');");
    }
}
