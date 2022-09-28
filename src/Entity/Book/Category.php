<?php

declare(strict_types=1);

namespace App\Entity\Book;

use App\Entity\AbstractEntity;
use App\Entity\Traits\NameEntityTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *     name="categories",
 * )
 */
class Category extends AbstractEntity
{
    use NameEntityTrait;
}
