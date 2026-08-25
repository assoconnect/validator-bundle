<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleXMLElement;

/**
 * Nothing fails at runtime when a catalogue is missing or incomplete: the violation message simply falls
 * back to its English default, so the gap only shows up in front of an end user.
 */
class TranslationCatalogueTest extends TestCase
{
    private const string TRANSLATIONS_DIR = __DIR__ . '/../translations';
    private const string REFERENCE_LOCALE = 'en';

    /** Every locale the catalogues are expected to cover. */
    private const array EXPECTED_LOCALES = ['en', 'fr', 'es'];

    /** @return iterable<string, array{locale: string}> */
    public static function provideLocales(): iterable
    {
        foreach (self::EXPECTED_LOCALES as $locale) {
            yield $locale => ['locale' => $locale];
        }
    }

    #[DataProvider('provideLocales')]
    public function testCatalogueExists(string $locale): void
    {
        self::assertFileExists(self::catalogPath($locale));
    }

    #[DataProvider('provideLocales')]
    public function testCatalogueCoversEveryReferenceMessage(string $locale): void
    {
        self::assertSame(
            $this->sources(self::REFERENCE_LOCALE),
            $this->sources($locale),
            sprintf('The %s catalogue does not cover the same messages as the %s one.', $locale, self::REFERENCE_LOCALE)
        );
    }

    #[DataProvider('provideLocales')]
    public function testEveryMessageIsTranslated(string $locale): void
    {
        foreach ($this->units($locale) as $unit) {
            self::assertNotSame(
                '',
                trim((string) $unit->children()->target),
                sprintf('Message "%s" has no %s translation.', $unit->children()->source, $locale)
            );
        }
    }

    private static function catalogPath(string $locale): string
    {
        return sprintf('%s/validators.%s.xlf', self::TRANSLATIONS_DIR, $locale);
    }

    /** @return list<string> */
    private function sources(string $locale): array
    {
        $sources = [];
        foreach ($this->units($locale) as $unit) {
            $sources[] = (string) $unit->children()->source;
        }
        sort($sources);

        return $sources;
    }

    /** @return list<SimpleXMLElement> */
    private function units(string $locale): array
    {
        $catalog = simplexml_load_file(self::catalogPath($locale));
        self::assertInstanceOf(
            SimpleXMLElement::class,
            $catalog,
            sprintf('The %s catalogue is not valid XML.', $locale)
        );

        // local-name() keeps the query independent from the XLIFF namespace declared on the root element
        $units = $catalog->xpath('//*[local-name()="trans-unit"]');
        self::assertIsArray($units, sprintf('The %s catalogue holds no translation unit.', $locale));

        return array_values($units);
    }
}
