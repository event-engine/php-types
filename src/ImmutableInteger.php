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
use function is_int;
use function is_object;
use function method_exists;

/**
 * Trait ImmutableInteger
 * @package EventEngine\Type
 *
 * @psalm-immutable
 */
trait ImmutableInteger
{
    /**
     * @psalm-readonly
     */
    public int $value;

    /**
     * @param int $value
     * @return static
     */
    public static function fromInt(int $value): self
    {
        return new self($value);
    }

    public function __construct(int $value)
    {
        if (isset($this->value)) {
            throw new BadMethodCallException(__METHOD__ . ' called on existing object!');
        }

        $this->value = $value;
    }

    public function toInt(): int
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string)$this->value;
    }

    public function equals($value): bool
    {
        if (is_object($value) && method_exists($value, 'toInt')) {
            return $this->value === $value->toInt();
        }

        if (is_int($value)) {
            return $this->value === $value;
        }

        return false;
    }
}
