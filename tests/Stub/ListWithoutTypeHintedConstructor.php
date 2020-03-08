<?php
declare(strict_types=1);

namespace EventEngineTest\Type\Stub;

use EventEngine\Type\ImmutableList;

final class ListWithoutTypeHintedConstructor
{
    use ImmutableList;

    private function __construct(...$items)
    {
        $this->items = $items;
    }
}
