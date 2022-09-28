<?php

declare(strict_types=1);

namespace App\Loader;

use App\Repository\MessageRepository;
use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\MessageCatalogue;

class DatabaseTranslationLoader implements LoaderInterface
{
    private static array $localeCatalogues = [];

    public function __construct(
        private MessageRepository $messageRepository
    ) {
    }

    public function load(mixed $resource, string $locale, string $domain = 'messages'): MessageCatalogue
    {
        if (!isset(self::$localeCatalogues[$locale])) {
            $catalogue = new MessageCatalogue($locale);

            $messages = $this->messageRepository->getAllWithTranslations($locale);

            foreach ($messages as $message) {
                $catalogue->set($message->getCode(), $message->translate($locale)->getValue(), $domain);
            }

            self::$localeCatalogues[$locale] = $catalogue;
        }

        return self::$localeCatalogues[$locale];
    }
}
