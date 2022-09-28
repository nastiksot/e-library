<?php

declare(strict_types=1);

namespace App\Repository\Settings;

use App\Contracts\Entity\EntityInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class AbstractSettingsRepository extends ServiceEntityRepository
{
    public function getSettings(): EntityInterface
    {
        return $this->findOneBy([]);
    }
}
