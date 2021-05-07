<?php
declare(strict_types=1);

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;

abstract class AbstractRepository extends ServiceEntityRepository
{

    protected function getConnection(): Connection
    {
        return $this->getEntityManager()->getConnection();
    }

}
