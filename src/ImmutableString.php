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

    private function __construct(string $value)
    {
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
}
