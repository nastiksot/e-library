<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\DataFixtures\Traits\EntityDataFixtureTrait;
use App\DataFixtures\Traits\ReadFileFixtureTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;

abstract class AbstractFixture extends Fixture
{
    use ReadFileFixtureTrait;
    use EntityDataFixtureTrait;
}
