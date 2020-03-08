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

use EventEngine\Data\ImmutableRecordLogic;
use ReflectionClass;
use RuntimeException;
use function array_push;
use function array_unshift;
use function array_values;
use function count;
use function is_array;
use function is_object;
use function method_exists;

/**
 * @template T
 * @psalm-immutable
 */
trait ImmutableList
{
    /**
     * ImmutableRecord is used for item type handling
     *
     * Methods fromArray, toArray and fromRecordData are overridden by ImmutableList
     * ImmutableRecordLogic constructor should be overridden by final collection class
     * to define the item type:
     *
     * @example constructor
     *
     * public function __construct(ItemType ...$items)
     * {
     *    $this->items = $items;
     * }
     */
    use ImmutableRecordLogic {
        ImmutableRecordLogic::toArray as immutableRecordToArray;
    }

    /**
     * @var array<array-key, T>
     * @psalm-readonly
     */
    public array $items;

    /**
     * @param array $recordData
     * @return self
     * @psalm-suppress MixedArgument
     * @psalm-external-mutation-free
     * @throws \ReflectionException
     */
    public static function fromRecordData(array $recordData)
    {
        self::ensurePropTypeMapIsBuilt();

        return new self(...$recordData);
    }

    /**
     * @param array $nativeData
     * @psalm-external-mutation-free
     * @return self
     * @throws \ReflectionException
     */
    public static function fromArray(array $nativeData)
    {
        self::ensurePropTypeMapIsBuilt();

        $self = new self();
        $self->setNativeData(['items' => $nativeData]);
        return $self;
    }

    /**
     * @psalm-param T ...$item
     * @psalm-external-mutation-free
     * @return self
     * @throws \ReflectionException
     */
    public function push(...$items): self
    {
        self::ensurePropTypeMapIsBuilt();
        $this->assertType('items', $items);

        $self = clone $this;
        array_push($self->items, ...$items);
        return $self;
    }

    /**
     * @psalm-param T ...$items
     * @return $this
     * @throws \ReflectionException
     */
    public function unshift(...$items): self
    {
        self::ensurePropTypeMapIsBuilt();
        $this->assertType('items', $items);

        $self = clone $this;
        array_unshift($self->items, ...$items);
        return $self;
    }

    /**
     * @psalm-external-mutation-free
     * @return self
     */
    public function pop(): self
    {
        if(count($this->items) === 0) {
            return $this;
        }

        $self = clone $this;
        unset($self->items[count($self->items) - 1]);

        return $self;
    }

    /**
     * @psalm-external-mutation-free
     * @return self
     */
    public function shift(): self
    {
        if(count($this->items) === 0) {
            return $this;
        }

        $self = clone $this;
        unset($self->items[0]);
        $self->items = array_values($self->items);

        return $self;
    }

    /**
     * @return T|null
     */
    public function first()
    {
        if(count($this->items)) {
            return $this->items[0];
        }

        return null;
    }

    /**
     * @return T|null
     */
    public function last()
    {
        if(count($this->items)) {
            return $this->items[count($this->items) - 1];
        }

        return null;
    }

    /**
     * @param callable $filter
     * @return static
     */
    public function filter(callable $filter): self
    {
        $filteredItems = [];

        foreach ($this->items as $item) {
            if($filter($item)) {
                $filteredItems[] = $item;
            }
        }

        $self = clone $this;
        $self->items = $filteredItems;
        return $self;
    }

    public function equals($other): bool
    {
        if(is_object($other) && method_exists($other, 'toArray')) {
            return $this->items == $other->toArray();
        }

        if(is_array($other)) {
            return $this->items == $other;
        }

        return false;
    }

    /**
     * @return array<array-key, mixed>
     * @psalm-external-mutation-free
     * @throws \ReflectionException
     */
    public function toArray(): array
    {
        self::ensurePropTypeMapIsBuilt();

        /** @var array{items: array<array-key, mixed>} $record */
        $record = $this->immutableRecordToArray();

        return $record['items'];
    }

    /**
     * Implementation of ImmutableRecord::arrayPropItemTypeMap()
     *
     * Uses constructor param type hint to detect item type of the collection
     *
     * @return array
     * @throws \ReflectionException
     */
    private static function arrayPropItemTypeMap(): array
    {
        $ref = new ReflectionClass(__CLASS__);

        $constructor = $ref->getConstructor();

        $errorMsg = __CLASS__ . " misses a public constructor that defines the item type of the collection.";

        if (null === $constructor) {
            throw new RuntimeException($errorMsg);
        }

        if ($constructor->getNumberOfParameters() !== 1) {
            throw new RuntimeException($errorMsg);
        }

        $params = $constructor->getParameters();

        foreach ($params as $param) {
            if (!$param->hasType()) {
                throw new RuntimeException($errorMsg);
            }

            $type = $param->getType();

            if (null === $type) {
                return [];
            }

            return ['items' => $type->getName()];
        }

        return [];
    }

    /**
     * @throws \ReflectionException
     */
    private static function ensurePropTypeMapIsBuilt(): void
    {
        if (null === self::$__propTypeMap) {
            self::$__propTypeMap = self::buildPropTypeMap();
        }

        if(null === self::$__arrayPropItemTypeMap) {
            self::$__arrayPropItemTypeMap = self::arrayPropItemTypeMap();
        }
    }
}
