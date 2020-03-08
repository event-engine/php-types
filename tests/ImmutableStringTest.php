<?php
/**
 * This file is part of event-engine/php-types.
 * (c) 2020 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EventEngineTest\Type;

use BadMethodCallException;
use EventEngineTest\Type\Stub\Username;
use PHPUnit\Framework\TestCase;

final class ImmutableStringTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_created_from_string()
    {
        $username = new Username('Jane');

        $this->assertEquals('Jane', $username->value);
        $this->assertEquals('Jane', $username->toString());
        $this->assertEquals('Jane', (string)$username);
    }

    /**
     * @test
     */
    public function it_equals_other_strings_with_same_value()
    {
        $username = Username::fromString('Jane');
        $other = Username::fromString('Jane');

        $this->assertTrue($username->equals($other));
        $this->assertTrue($username->equals('Jane'));
        $this->assertFalse($username->equals('John'));
    }

    /**
     * @test
     */
    public function it_prevents_double_constructor_calls()
    {
        $username = new Username('Jane');

        $this->expectException(BadMethodCallException::class);

        $username->__construct('John');
    }
}
