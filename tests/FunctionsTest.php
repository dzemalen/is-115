<?php

use PHPUnit\Framework\TestCase;

final class FunctionsTest extends TestCase
{
    public function testEscapesHtml(): void
    {
        $input = '<script>alert("x")</script>';
        $this->assertSame('&lt;script&gt;alert(&quot;x&quot;)&lt;/script&gt;', k($input));
    }

    public function testValidatesMobilnummer(): void
    {
        $this->assertTrue(valider_mobil('12345678'));
        $this->assertTrue(valider_mobil('12 34 56 78'));
        $this->assertFalse(valider_mobil('1234'));
        $this->assertFalse(valider_mobil('1234567a'));
    }

    public function testPasswordRules(): void
    {
        $this->assertFalse(is_valid_password('kort1!'));
        $this->assertTrue(is_valid_password('Sterk12!'));
    }
}
