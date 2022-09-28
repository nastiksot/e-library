<?php

declare(strict_types=1);

namespace App\Twig;

use App\Contracts\Entity\EntityInterface;
use App\Entity\Page\AbstractPage;
use App\Entity\Partner;
use App\Entity\Product\Product;
use App\Entity\Product\ProductSet;
use App\Entity\User\Dealer;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use function method_exists;

class ImageTypeExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('basename', 'basename'),
            new TwigFunction('getImageType', [$this, 'getImageType']),
        ];
    }

    public function getImageType(EntityInterface $entity): string
    {
        return match (true) {
            $entity instanceof AbstractPage => 'page',
            $entity instanceof Product      => 'product',
            $entity instanceof ProductSet   => 'product_set',
            $entity instanceof Partner      => 'partner',
            $entity instanceof Dealer       => 'dealer',
            default                         => 'default',
        };
    }

    public function getImageTitle(EntityInterface $entity): ?string
    {
        if (method_exists($entity, 'translate') && method_exists($entity->translate(), 'getImageTitle')) {
            return $entity->translate()->getImageTitle();
        }

        if (method_exists($entity, 'translate') && method_exists($entity->translate(), '__toString')) {
            return $entity->translate()->__toString();
        }

        if (method_exists($entity, 'translate') && method_exists($entity->translate(), 'getTitle')) {
            return $entity->translate()->getTitle();
        }

        if (method_exists($entity, 'getImageTitle')) {
            return $entity->getImageTitle();
        }

        if (method_exists($entity, '__toString')) {
            return $entity->__toString();
        }

        if (method_exists($entity, 'getTitle')) {
            return $entity->getTitle();
        }

        return null;
    }
}
