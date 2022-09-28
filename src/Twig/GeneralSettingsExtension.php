<?php

declare(strict_types=1);

namespace App\Twig;

use App\Repository\Settings\GeneralSettingsRepository;
use InvalidArgumentException;
use RuntimeException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use function method_exists;
use function preg_replace;
use function ucfirst;

class GeneralSettingsExtension extends AbstractExtension
{
    public function __construct(
        private GeneralSettingsRepository $generalSettingsRepository,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getGeneralSettings', [$this, 'getGeneralSettings']),
        ];
    }

    /**
     * @return array|TwigFilter[]
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function getFilters(): array
    {
        $onlyDigitsFilter = new TwigFilter(
            'only_digits', static function ($number) {
                return preg_replace('/[^0-9]/', '', $number);
            }, ['is_safe' => ['all']]
        );

        return [
            $onlyDigitsFilter,
        ];
    }

    public function getGeneralSettings(string $settingName = null, $default = null): mixed
    {
        static $generalSettings;

        if (null === $generalSettings) {
            $generalSettings = $this->generalSettingsRepository->getSettings();
        }

        if (null !== $settingName && $generalSettings) {
            $method = 'get' . ucfirst($settingName);

            return method_exists($generalSettings, $method)
                ? $generalSettings->$method()
                : $default;
        }

        return $generalSettings;
    }
}
