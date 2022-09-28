<?php

declare(strict_types=1);

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use function date_default_timezone_set;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function getLogDir(): string
    {
        return $this->getProjectDir() . '/var/log/' . $this->getEnvironment();
    }

    public function boot(): void
    {
        parent::boot();

        date_default_timezone_set($this->container->getParameter('timezone'));
    }
}
