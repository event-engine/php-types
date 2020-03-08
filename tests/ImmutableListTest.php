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

use EventEngineTest\Type\Stub\ListWithoutConstructor;
use EventEngineTest\Type\Stub\ListWithoutTypeHintedConstructor;
use EventEngineTest\Type\Stub\ListWithWrongConstructor;
use EventEngineTest\Type\Stub\Username;
use EventEngineTest\Type\Stub\UsernameList;
use PHPUnit\Framework\TestCase;

final class ImmutableListTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_list_from_items()
    {
        $user1 = Username::fromString('John');
        $user2 = Username::fromString('Jane');

        $list = new UsernameList($user1, $user2);

        $this->assertEquals([
            'John',
            'Jane',
        ], $list->toArray());
    }

    /**
     * @test
     */
    public function it_creates_list_from_array()
    {
        $list = UsernameList::fromArray(['John', 'Jane']);

        $this->assertEquals([
            'John',
            'Jane',
        ], $list->toArray());
    }

    /**
     * @test
     */
    public function it_creates_list_from_record_data()
    {
        $list = UsernameList::fromRecordData([
            Username::fromString('John'),
            Username::fromString('Jane'),
        ]);

        $this->assertEquals([
            'John',
            'Jane',
        ], $list->toArray());
    }

    /**
     * @test
     */
    public function it_pushes_items_to_list()
    {
        $list = UsernameList::fromRecordData([
            Username::fromString('John'),
            Username::fromString('Jane'),
        ]);

        $newList = $list->push(
            Username::fromString('Max'),
            Username::fromString('Maxi')
        );

        $this->assertEquals([
            'John',
            'Jane',
        ], $list->toArray());

        $this->assertEquals([
            'John',
            'Jane',
            'Max',
            'Maxi',
        ], $newList->toArray());
    }

    /**
     * @test
     */
    public function it_returns_first_item()
    {
        $list = UsernameList::fromRecordData([
            Username::fromString('John'),
            Username::fromString('Jane'),
        ]);

        $this->assertEquals('John', $list->first()->toString());
    }

    /**
     * @test
     */
    public function it_returns_null_for_first_item_if_list_is_empty()
    {
        $list = new UsernameList();

        $this->assertNull($list->first());
    }

    /**
     * @test
     */
    public function it_returns_last_item()
    {
        $list = UsernameList::fromRecordData([
            Username::fromString('John'),
            Username::fromString('Jane'),
            Username::fromString('Max'),
        ]);

        $this->assertEquals('Max', $list->last()->toString());
    }

    /**
     * @test
     */
    public function it_returns_null_for_last_item_if_list_is_empty()
    {
        $list = new UsernameList();

        $this->assertNull($list->last());
    }

    /**
     * @test
     */
    public function it_pops_last_item()
    {
        $list = UsernameList::fromRecordData([
            Username::fromString('John'),
            Username::fromString('Jane'),
            Username::fromString('Max'),
        ]);

        $newList = $list->pop();

        $this->assertEquals([
            'John',
            'Jane',
            'Max',
        ], $list->toArray());

        $this->assertEquals([
            'John',
            'Jane',
        ], $newList->toArray());
    }

    /**
     * @test
     */
    public function it_pops_nothing_if_list_is_empty()
    {
        $list = new UsernameList();
        $newList = $list->pop();

        $this->assertSame($list, $newList);
    }

    /**
     * @test
     */
    public function it_shifts_first_item()
    {
        $list = UsernameList::fromRecordData([
            Username::fromString('John'),
            Username::fromString('Jane'),
            Username::fromString('Max'),
        ]);

        $newList = $list->shift();

        $this->assertEquals([
            'John',
            'Jane',
            'Max',
        ], $list->toArray());

        $this->assertEquals([
            'Jane',
            'Max',
        ], $newList->toArray());
    }

    /**
     * @test
     */
    public function it_shifts_nothing_if_list_is_empty()
    {
        $list = new UsernameList();
        $newList = $list->shift();

        $this->assertSame($list, $newList);
    }

    /**
     * @test
     */
    public function it_unshifts_items_to_list()
    {
        $list = UsernameList::fromRecordData([
            Username::fromString('John'),
            Username::fromString('Jane'),
        ]);

        $newList = $list->unshift(
            Username::fromString('Max'),
            Username::fromString('Maxi')
        );

        $this->assertEquals([
            'John',
            'Jane',
        ], $list->toArray());

        $this->assertEquals([
            'Max',
            'Maxi',
            'John',
            'Jane',
        ], $newList->toArray());
    }

    /**
     * @test
     */
    public function it_filters_items()
    {
        $list = UsernameList::fromRecordData([
            Username::fromString('John'),
            Username::fromString('Jane'),
            Username::fromString('Max'),
        ]);

        $newList = $list->filter(function (Username $username) {
            return $username->toString() !== 'Max';
        });

        $this->assertEquals([
            'John',
            'Jane',
            'Max',
        ], $list->toArray());

        $this->assertEquals([
            'John',
            'Jane',
        ], $newList->toArray());
    }

    /**
     * @test
     * @dataProvider provideListsWithWrongConstructor
     */
    public function it_ensures_correct_constructor_in_list_class(string $listClass)
    {
        $this->expectExceptionMessage($listClass . ' misses a public constructor that defines the item type of the collection.');

        $list = $listClass::fromArray([]);
    }

    public function provideListsWithWrongConstructor(): array
    {
        return [
            [ListWithoutConstructor::class],
            [ListWithWrongConstructor::class],
            [ListWithoutTypeHintedConstructor::class]
        ];
    }

    /**
     * @test
     * @dataProvider provideEqualLists
     */
    public function it_equals_list_with_same_items($otherList, bool $equals)
    {
        $list = UsernameList::fromRecordData([
            Username::fromString('John'),
            Username::fromString('Jane'),
        ]);

        if($equals) {
            $this->assertTrue($list->equals($otherList));
        } else {
            $this->assertFalse($list->equals($otherList));
        }
    }

    public function provideEqualLists(): array
    {
        return [
            [
                UsernameList::fromRecordData([
                    Username::fromString('John'),
                    Username::fromString('Jane'),
                ]),
                true
            ],
            [
                [
                    Username::fromString('John'),
                    Username::fromString('Jane'),
                ],
                true
            ],
            [
                "Max",
                false
            ],
        ];
    }
}
