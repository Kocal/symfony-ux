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

namespace Symfony\UX\Map\Tests\Registry;

use PHPUnit\Framework\TestCase;
use Symfony\UX\Map\Provider\GoogleMaps;
use Symfony\UX\Map\Registry\MapRegistry;

final class MapRegistryTest extends TestCase
{
    public function testBehavior(): void
    {
        $mapRegistry = new MapRegistry();
        self::assertEmpty($mapRegistry->all());

        $mapRegistry->register($map1 = new GoogleMaps\Map('my_map'));
        self::assertContains($map1, $mapRegistry->all());

        $mapRegistry->register($map2 = new GoogleMaps\Map('my_map'));
        self::assertContains($map2, $mapRegistry->all());

        $mapRegistry->reset();
        self::assertEmpty($mapRegistry->all());
    }
}
