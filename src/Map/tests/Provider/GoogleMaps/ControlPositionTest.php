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
use Symfony\UX\Map\Provider\GoogleMaps\ControlPosition;

class ControlPositionTest extends TestCase
{
    public function testEnumValues(): void
    {
        self::assertSame(24, ControlPosition::BLOCK_END_INLINE_CENTER->value);
        self::assertSame(25, ControlPosition::BLOCK_END_INLINE_END->value);
        self::assertSame(23, ControlPosition::BLOCK_END_INLINE_START->value);
        self::assertSame(15, ControlPosition::BLOCK_START_INLINE_CENTER->value);
        self::assertSame(16, ControlPosition::BLOCK_START_INLINE_END->value);
        self::assertSame(14, ControlPosition::BLOCK_START_INLINE_START->value);
        self::assertSame(11, ControlPosition::BOTTOM_CENTER->value);
        self::assertSame(10, ControlPosition::BOTTOM_LEFT->value);
        self::assertSame(12, ControlPosition::BOTTOM_RIGHT->value);
        self::assertSame(21, ControlPosition::INLINE_END_BLOCK_CENTER->value);
        self::assertSame(22, ControlPosition::INLINE_END_BLOCK_END->value);
        self::assertSame(20, ControlPosition::INLINE_END_BLOCK_START->value);
        self::assertSame(17, ControlPosition::INLINE_START_BLOCK_CENTER->value);
        self::assertSame(19, ControlPosition::INLINE_START_BLOCK_END->value);
        self::assertSame(18, ControlPosition::INLINE_START_BLOCK_START->value);
        self::assertSame(6, ControlPosition::LEFT_BOTTOM->value);
        self::assertSame(4, ControlPosition::LEFT_CENTER->value);
        self::assertSame(5, ControlPosition::LEFT_TOP->value);
        self::assertSame(9, ControlPosition::RIGHT_BOTTOM->value);
        self::assertSame(8, ControlPosition::RIGHT_CENTER->value);
        self::assertSame(7, ControlPosition::RIGHT_TOP->value);
        self::assertSame(2, ControlPosition::TOP_CENTER->value);
        self::assertSame(1, ControlPosition::TOP_LEFT->value);
        self::assertSame(3, ControlPosition::TOP_RIGHT->value);
    }
}
