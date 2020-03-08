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
use EventEngineTest\Type\Stub\Access;
use PHPUnit\Framework\TestCase;

final class ImmutableBooleanTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_created_from_boolean()
    {
        $access = Access::fromBool(true);

        $this->assertTrue($access->value);
        $this->assertTrue($access->toBool());
        $this->assertEquals('TRUE', (string)$access);

        $access = Access::fromBool(false);
        $this->assertEquals('FALSE', (string)$access);
    }

    /**
     * @test
     */
    public function it_equals_other_booleans_with_same_value()
    {
        $access = Access::fromBool(true);
        $other = Access::fromBool(true);

        $this->assertTrue($access->equals($other));
        $this->assertTrue($access->equals(true));
        $this->assertFalse($access->equals(false));
        $this->assertFalse($access->equals('test'));
    }

    /**
     * @test
     */
    public function it_prevents_double_constructor_calls()
    {
        $access = new Access(true);

        $this->expectException(BadMethodCallException::class);

        $access->__construct(false);
    }
}
