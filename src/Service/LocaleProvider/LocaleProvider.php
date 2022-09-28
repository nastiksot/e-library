<?php

declare(strict_types=1);

namespace App\Service\LocaleProvider;

use A2lix\TranslationFormBundle\Locale\LocaleProviderInterface;
use App\Repository\CountryRepository;
use function array_column;
use function array_combine;
use function strcmp;
use function strtolower;
use function strtoupper;
use function usort;

class LocaleProvider //implements LocaleProviderInterface
{
//    private ?array $locales = null;
//
//    private ?array $localeTitlesForAdmin = null;
//
//    private ?array $summaryLocalesData = null;
//
//    public function __construct(
//        private CountryRepository $countryRepository,
//        private string $defaultLocale,
//        private string $adminLocale,
//    ) {
//    }
//
//    public function initLocaleData(?string $currentLocale = null): void
//    {
//        $countries = $this->countryRepository->getCountryLanguageCombinations($currentLocale);
//
//        $this->summaryLocalesData   = [];
//        $this->localeTitlesForAdmin = [];
//
//        foreach ($countries as $country) {
//            $upperCountryCode = strtoupper($country->getCode());
//            $lowerCountryCode = strtolower($country->getCode());
//
//            $countryLanguages = $country->getLanguages();
//
//            foreach ($countryLanguages as $language) {
//                $lowerLangCode = strtolower($language->getCode());
//
//                $locale = $lowerLangCode . '_' . $upperCountryCode;
//
//                $countryTitle   = $country->translate($locale)->getTitle() ?? $lowerCountryCode;
//                $languageTitle  = $language->translate($locale)->getTitle() ?? $lowerLangCode;
//                $enCountryTitle = $country->translate('en_GB')->getTitle() ?? $countryTitle;
//
//                // Frontend titles
//                $this->summaryLocalesData[] = [
//                    'prefix'    => $lowerLangCode . '-' . $lowerCountryCode,
//                    'locale'    => $locale,
//                    'title'     => $countryTitle . ' (' . $languageTitle . ')',
//                    'enCountry' => $enCountryTitle,
//                ];
//
//                // Admin titles
//                $countryTitle  = $country->translate($this->adminLocale)->getTitle() ?? $lowerCountryCode;
//                $languageTitle = $language->translate($this->adminLocale)->getTitle() ?? $lowerLangCode;
//
//                $this->localeTitlesForAdmin[] = [
//                    $locale => $countryTitle . ' (' . $languageTitle . ')',
//                ];
//            }
//        }
//
//        usort($this->summaryLocalesData, static function (array $a, array $b) {
//            return strcmp($a['title'], $b['title']);
//        });
//
//        $this->locales = array_column($this->summaryLocalesData, 'locale');
//
//        $this->localeTitlesForAdmin = array_combine(
//            $this->locales,
//            array_column($this->summaryLocalesData, 'title')
//        );
//    }
//
//    public function getLocales(): array
//    {
//        if (null === $this->locales) {
//            $this->initLocaleData();
//        }
//
//        return $this->locales;
//    }
//
//    public function getDefaultLocale(): string
//    {
//        return $this->defaultLocale;
//    }
//
//    public function getRequiredLocales(): array
//    {
//        return $this->getLocales();
//    }
//
//    public function getLocaleTitlesForAdmin(): array
//    {
//        if (null === $this->localeTitlesForAdmin) {
//            $this->initLocaleData();
//        }
//
//        return $this->localeTitlesForAdmin;
//    }
//
//    public function getSummaryLocalesData(?string $currentLocale = null): array
//    {
//        if (null === $this->summaryLocalesData) {
//            $this->initLocaleData($currentLocale);
//        }
//
//        return $this->summaryLocalesData;
//    }
}
