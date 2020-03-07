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
     * @psalm-param T $item
     * @psalm-external-mutation-free
     * @return self
     */
    public function push($item): self
    {
        self::ensurePropTypeMapIsBuilt();
        $this->assertType('items', [$item]);

        $self = clone $this;
        $self->items[] = $item;
        return $self;
    }

    /**
     * @param array $recordData
     * @return self
     * @psalm-suppress MixedArgument
     * @psalm-external-mutation-free
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
     */
    public static function fromArray(array $nativeData)
    {
        self::ensurePropTypeMapIsBuilt();;

        $self = new self();
        $self->setNativeData(['items' => $nativeData]);
        return $self;
    }

    /**
     * @return array<array-key, mixed>
     * @psalm-external-mutation-free
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

        if(null === $constructor) {
            throw new RuntimeException($errorMsg);
        }

        if(!$constructor->isPublic()) {
            throw new RuntimeException($errorMsg);
        }

        if($constructor->getNumberOfParameters() !== 1) {
            throw new RuntimeException($errorMsg);
        }

        $params = $constructor->getParameters();

        foreach ($params as $param) {
            if(!$param->hasType()) {
                throw new RuntimeException($errorMsg);
            }

            $type = $param->getType();

            if(null === $type) {
                return [];
            }

            return ['items' => $type->getName()];
        }

        return [];
    }

    private static function ensurePropTypeMapIsBuilt(): void
    {
        if (null === self::$__propTypeMap) {
            self::$__propTypeMap = self::buildPropTypeMap();
        }
    }
}
