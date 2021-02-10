<?php

namespace App\Domain\Reviews;

class UserDetector
{
    private const LETTER_REPLACEMENTS = [
        'B' => '8',
        'O' => '0',
    ];

    /**
     * @param string $text
     * @return array
     */
    public static function guessNames(string $text): array
    {
        $matches = null;
        $names = [];

        self::getDirectName($names, $text);
        self::guessManglingFirstTwoChars($names, $text);
        self::guessManglingLastChar($names, $text);
        self::findWordPrefixedUser($names, $text);
        self::findWordSuffixedUser($names, $text);

        return array_unique(
            array_map(fn ($name) => strtoupper($name), $names)
        );
    }

    /**
     * Where there is a clear username with border on each side
     * eg. "Great service from SC8 last yesterday"
     *
     * @param array &$names
     * @param string $text
     */
    private static function getDirectName(array &$names, string $text): void
    {
        if (preg_match('/\b[a-zA-Z]{2}[0-9]{1}\b/', $text, $matches)) {
            $names[] = strtoupper($matches[0]);
        }
    }

    /**
     * Were the first/second number of a three alphanumeric word could be a letter
     * eg. "Great service from R08 last yesterday"
     * could be: "Great service from R08 last yesterday"
     *
     * @param array &$names
     * @param string $text
     */
    private static function guessManglingFirstTwoChars(array &$names, string $text): void
    {
        $matches = null;
        if (preg_match_all('/\b[a-zA-Z0-9]{2}[0-9]{1}\b/', $text, $matches, PREG_SET_ORDER, 0)) {
            $letterReplacements = array_flip(self::LETTER_REPLACEMENTS);

            foreach ($matches as $match) {
                $match[0] = strtoupper($match[0]);

                $firstLetter = substr($match[0], 0, 1);
                $secondLetter = substr($match[0], 1, 1);

                if (isset($letterReplacements[$firstLetter])) {
                    $firstLetter = $letterReplacements[$firstLetter];
                }
                if (isset($letterReplacements[$secondLetter])) {
                    $secondLetter = $letterReplacements[$secondLetter];
                }

                $newName = $firstLetter . $secondLetter . substr($match[0], 2, 1);

                if (preg_match('/\b[a-zA-Z]{2}[0-9]{1}\b/', $newName)) {
                    $names[] = $newName;
                }
            }
        }
    }

    /**
     * Were the last number of a three alphanumeric word could be a number
     * eg. "Great service from SCB last yesterday"
     * could be "Great service from SC8 last yesterday"
     *
     * @param array &$names
     * @param string $text
     */
    private static function guessManglingLastChar(array &$names, string $text): void
    {
        $matches = null;
        if (preg_match_all('/\b[a-zA-Z]{3}\b/', $text, $matches, PREG_SET_ORDER, 0)) {
            foreach ($matches as $match) {
                $match[0] = strtoupper($match[0]);
                $lastLetter = substr($match[0], 2);

                if (isset(self::LETTER_REPLACEMENTS[$lastLetter])) {
                    $names[] = substr($match[0], 0, 2) . self::LETTER_REPLACEMENTS[$lastLetter];
                }
            }
        }
    }

    /**
     * Where the username might have no space on the right
     * eg. "Great service from SC8last yesterday"
     * could be "Great service from SC8 last yesterday"
     *
     * @param array &$names
     * @param string $text
     */
    private static function findWordPrefixedUser(array &$names, string $text): void
    {
        $matches = null;
        if (preg_match_all('/\b[A-Z]{2}[0-9]{1}/', $text, $matches, PREG_SET_ORDER, 0)) {
            foreach ($matches as $match) {
                $names[] = strtoupper($match[0]);
            }
        }
    }

    /**
     * Where the username might have no space on the left
     * eg. "Great service fromSC8 last yesterday"
     * could be "Great service from SC8 last yesterday"
     *
     * @param array &$names
     * @param string $text
     */
    private static function findWordSuffixedUser(array &$names, string $text): void
    {
        $matches = null;
        if (preg_match_all('/[A-Z]{2}[0-9]{1}\b/', $text, $matches, PREG_SET_ORDER, 0)) {
            foreach ($matches as $match) {
                $names[] = strtoupper($match[0]);
            }
        }
    }
}
