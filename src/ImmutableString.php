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
use function is_object;
use function is_string;
use function method_exists;

/**
 * Trait ImmutableString
 *
 * @package EventEngine\Type
 *
 * @psalm-immutable
 */
trait ImmutableString
{
    /**
     * @psalm-readonly
     */
    public string $value;

    /**
     * @param string $value
     * @return static
     */
    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function __construct(string $value)
    {
        if (isset($this->value)) {
            throw new BadMethodCallException(__METHOD__ . ' called on existing object!');
        }

        $this->value = $value;
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals($value): bool
    {
        if (is_object($value) && method_exists($value, 'toString')) {
            return $this->value === $value->toString();
        }

        if (is_string($value)) {
            return $this->value === $value;
        }

        return false;
    }
}
