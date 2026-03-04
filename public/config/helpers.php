<?php

declare(strict_types=1);

/**
 * Sanitize a string value: strip tags and trim whitespace.
 */
function clean(?string $value): string
{
    return trim((string) strip_tags((string) $value));
}

/**
 * Truncate a string to a maximum character length.
 */
function truncate(string $value, int $max): string
{
    return mb_substr($value, 0, $max);
}
