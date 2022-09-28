<?php

declare(strict_types=1);

namespace App\Twig;

use App\Contracts\Entity\LinkEntityInterface;
use App\Entity\Page\AbstractPage;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class LinkExtension extends AbstractExtension
{
    public function __construct(
        private RouterInterface $router,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getLink', [$this, 'getLink']),
            new TwigFunction('getLinkFromParams', [$this, 'getLinkFromParams']),
            new TwigFunction('getLinkAttr', [$this, 'getLinkAttr']),
            new TwigFunction('getLinkAttrFromParams', [$this, 'getLinkAttrFromParams']),
        ];
    }

    public function getLink(LinkEntityInterface $entity, int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH, ?string $locale = null): ?string
    {
        return $this->getLinkFromParams(
            $entity->getLinkType(),
            $entity->getLinkPage(),
            $entity->getLink(),
            $referenceType,
            $locale
        );
    }

    public function getLinkFromParams(?string $linkType, ?AbstractPage $targetPage, ?string $link, int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH, ?string $locale = null): ?string
    {
        // link to the local site page
        if ($targetPage && LinkEntityInterface::LINK_TYPE_PAGE === $linkType) {
            return $this->router->generate('web.cms', ['slug' => $targetPage->getSlug(), '_locale' => $locale], $referenceType);
        }

        // link to the external site
        if ($link && LinkEntityInterface::LINK_TYPE_EXTERNAL_LINK === $linkType) {
            return $link;
        }

        // by default link not set
        return null;
    }

    public function getLinkAttr(LinkEntityInterface $entity): ?string
    {
        return $this->getLinkAttrFromParams($entity->getLinkTarget(), $entity->isNoFollow());
    }

    public function getLinkAttrFromParams(?string $target = LinkEntityInterface::TARGET_TOP, ?bool $isNoFollow = false): ?string
    {
        $attributes = '';
        $attributes .= $target ? ' target="' . $target . '" ' : '';
        $attributes .= $isNoFollow ? ' rel="nofollow" ' : '';

        return $attributes ?? null;
    }
}
