<?php
/**
 * This file is part of event-engine/php-types.
 * (c) 2020 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EventEngineTest\Type\Stub;

use EventEngine\Type\ImmutableInteger;

final class Version
{
    use ImmutableInteger;
}
