<?php

declare(strict_types=1);

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\UX\Map\Tests\Provider\GoogleMaps;

use PHPUnit\Framework\TestCase;
use Symfony\UX\Map\Provider\GoogleMaps\GestureHandling;

class GestureHandlingTest extends TestCase
{
    public function testEnumValues(): void
    {
        self::assertSame('cooperative', GestureHandling::Cooperative->value);
        self::assertSame('greedy', GestureHandling::Greedy->value);
        self::assertSame('none', GestureHandling::None->value);
        self::assertSame('auto', GestureHandling::Auto->value);
    }
}
