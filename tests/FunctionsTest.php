<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class FunctionsTest extends TestCase
{
    public static function emailProvider(): array
    {
        return [
            ['valid@example.com', true],
            ['invalid-email', false],
        ];
    }

    #[DataProvider('emailProvider')]
    public function testIsValidEmail(string $email, bool $expected): void
    {
        self::assertSame($expected, is_valid_email($email));
    }

    public static function mobilProvider(): array
    {
        return [
            ['12345678', true],
            ['12 34 56 78', true],
            ['1234', false],
            ['1234567a', false],
        ];
    }

    #[DataProvider('mobilProvider')]
    public function testValiderMobil(string $mobil, bool $expected): void
    {
        self::assertSame($expected, valider_mobil($mobil));
    }

    public static function passwordProvider(): array
    {
        return [
            ['Ab12!cdef', true],
            ['short1!', false],
            ['lowercase12!', false],
            ['NoSpecial12', false],
        ];
    }

    #[DataProvider('passwordProvider')]
    public function testIsValidPassword(string $password, bool $expected): void
    {
        self::assertSame($expected, is_valid_password($password));
    }
}
