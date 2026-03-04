<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{
    // ─── clean() ─────────────────────────────────────────────────────────────

    public function testCleanStripsHtmlTags(): void
    {
        $this->assertSame('Hello World', clean('<p>Hello World</p>'));
    }

    public function testCleanTrimsLeadingAndTrailingWhitespace(): void
    {
        $this->assertSame('hello', clean('  hello  '));
    }

    public function testCleanHandlesNull(): void
    {
        $this->assertSame('', clean(null));
    }

    public function testCleanRemovesScriptTags(): void
    {
        $this->assertSame('alert("xss")', clean('<script>alert("xss")</script>'));
    }

    public function testCleanRemovesNestedTags(): void
    {
        $this->assertSame('bold text', clean('<b><i>bold text</i></b>'));
    }

    public function testCleanPreservesPlainText(): void
    {
        $this->assertSame('plain text', clean('plain text'));
    }

    public function testCleanHandlesEmptyString(): void
    {
        $this->assertSame('', clean(''));
    }

    // ─── truncate() ──────────────────────────────────────────────────────────

    public function testTruncateShortenLongString(): void
    {
        $this->assertSame('hello', truncate('hello world', 5));
    }

    public function testTruncateDoesNotChangeShortString(): void
    {
        $this->assertSame('hi', truncate('hi', 10));
    }

    public function testTruncateHandlesEmptyString(): void
    {
        $this->assertSame('', truncate('', 5));
    }

    public function testTruncateHandlesExactLength(): void
    {
        $this->assertSame('hello', truncate('hello', 5));
    }

    public function testTruncateHandlesMultibyteCharacters(): void
    {
        // Each accented character is one mb character
        $this->assertSame('olá', truncate('olá mundo', 3));
    }

    public function testTruncateWithZeroMax(): void
    {
        $this->assertSame('', truncate('hello', 0));
    }
}
