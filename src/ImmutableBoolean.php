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
use function is_bool;
use function is_object;
use function method_exists;

/**
 * Trait ImmutableBoolean
 * @package EventEngine\Type
 *
 * @psalm-immutable
 */
trait ImmutableBoolean
{
    /**
     * @psalm-readonly
     */
    public bool $value;

    /**
     * @param bool $value
     * @return static
     */
    public static function fromBool(bool $value): self
    {
        return new self($value);
    }

    public function __construct(bool $value)
    {
        if(isset($this->value)) {
            throw new BadMethodCallException(__METHOD__ . ' called on existing object!');
        }

        $this->value = $value;
    }

    public function toBool(): bool
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value ? 'TRUE' : 'FALSE';
    }

    public function equals($value): bool
    {
        if(is_object($value) && method_exists($value, 'toBool')) {
            return $this->value === $value->toBool();
        }

        if(is_bool($value)) {
            return $this->value === $value;
        }

        return false;
    }
}
