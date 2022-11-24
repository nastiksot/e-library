<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\DataFixtures\Traits\EntityDataFixtureTrait;
use App\DataFixtures\Traits\ReadFileFixtureTrait;
use App\Service\MessageBusHandler;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractFixture extends Fixture
{
    use ReadFileFixtureTrait;
    use EntityDataFixtureTrait;

    public function __construct(
        protected EntityManagerInterface $em,
        protected MessageBusHandler $messageBusHandler,
    ) {
    }
}
