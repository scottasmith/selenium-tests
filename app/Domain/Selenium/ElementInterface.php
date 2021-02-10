<?php

namespace App\Domain\Selenium;

interface ElementInterface
{
    const BY_CSS = 'css';
    const BY_CLASS = 'class';
    const BY_ID = 'id';
    const BY_LINK_TEXT = 'linkText';
    const BY_NAME = 'name';
    const BY_TAG_NAME = 'tagName';
    const BY_XPATH = 'xpath';

    const VALID_TYPES = [
        self::BY_CSS,
        self::BY_CLASS,
        self::BY_ID,
        self::BY_LINK_TEXT,
        self::BY_NAME,
        self::BY_TAG_NAME,
        self::BY_XPATH,
    ];
}
