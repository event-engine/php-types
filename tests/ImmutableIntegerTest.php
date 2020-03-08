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
use EventEngineTest\Type\Stub\Version;
use PHPUnit\Framework\TestCase;

final class ImmutableIntegerTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_created_from_integer()
    {
        $version = new Version(1);

        $this->assertEquals(1, $version->value);
        $this->assertEquals(1, $version->toInt());
        $this->assertEquals('1', (string)$version);
    }

    /**
     * @test
     */
    public function it_equals_other_integers_with_same_value()
    {
        $version = Version::fromInt(1);
        $other = Version::fromInt(1);

        $this->assertTrue($version->equals($other));
        $this->assertTrue($version->equals(1));
        $this->assertFalse($version->equals(2));
        $this->assertFalse($version->equals('test'));
    }

    /**
     * @test
     */
    public function it_prevents_double_constructor_calls()
    {
        $version = new Version(1);

        $this->expectException(BadMethodCallException::class);

        $version->__construct(2);
    }
}
