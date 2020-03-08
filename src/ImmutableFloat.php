<?php
/**
 * This file is part of event-engine/php-types.
 * (c) 2020 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EventEngine\Type;

use BadMethodCallException;
use function is_float;
use function is_object;
use function method_exists;

/**
 * Trait ImmutableFloat
 * @package EventEngine\Type
 *
 * @psalm-immutable
 */
trait ImmutableFloat
{
    /**
     * @psalm-readonly
     */
    public float $value;

    /**
     * @param float $value
     * @return static
     */
    public static function fromString(float $value): self
    {
        return new self($value);
    }

    public function __construct(float $value)
    {
        if (isset($this->value)) {
            throw new BadMethodCallException(__METHOD__ . ' called on existing object!');
        }

        $this->value = $value;
    }

    public function toFloat(): float
    {
        return (float)$this->value;
    }

    public function __toString(): string
    {
        return (string)$this->value;
    }

    public function equals($value): bool
    {
        if (is_object($value) && method_exists($value, 'toFloat')) {
            return $this->value === $value->toFloat();
        }

        if (is_float($value)) {
            return $this->value === $value;
        }

        return false;
    }
}
