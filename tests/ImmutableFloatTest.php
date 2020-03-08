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
use EventEngineTest\Type\Stub\Percentage;
use PHPUnit\Framework\TestCase;

final class ImmutableFloatTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_created_from_boolean()
    {
        $percentage = Percentage::fromFloat(0.5);

        $this->assertEquals(0.5, $percentage->value);
        $this->assertEquals(0.5, $percentage->toFloat());
        $this->assertEquals('0.5', (string)$percentage);
    }

    /**
     * @test
     */
    public function it_equals_other_floats_with_same_value()
    {
        $percentage = Percentage::fromFloat(0.5);
        $other = Percentage::fromFloat(0.5);

        $this->assertTrue($percentage->equals($other));
        $this->assertTrue($percentage->equals(0.5));
        $this->assertFalse($percentage->equals(0.6));
        $this->assertFalse($percentage->equals('test'));
    }

    /**
     * @test
     */
    public function it_prevents_double_constructor_calls()
    {
        $percentage = new Percentage(0.5);

        $this->expectException(BadMethodCallException::class);

        $percentage->__construct(0.6);
    }
}
