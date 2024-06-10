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

namespace Symfony\UX\Map\Tests\Provider\Leaflet;

use PHPUnit\Framework\TestCase;
use Symfony\UX\Map\Provider\Leaflet\Map;
use Symfony\UX\Map\Provider\Leaflet\MapFactory;

class MapFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $mapFactory = new MapFactory();

        $map = $mapFactory->createMap('map_name');

        self::assertInstanceOf(Map::class, $map);
        self::assertSame('map_name', $map->getName());

        $view = $map->createView();
        self::assertNull($view['center']);
        self::assertNull($view['zoom']);
        self::assertFalse($view['fitBoundsToMarkers']);
        self::assertEquals([
            'url' => 'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
            'attribution' => '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        ], $view['tileLayer']);
    }

    public function testCreateWithCustomOptions(): void
    {
        $mapFactory = new MapFactory();

        $map = $mapFactory->createMap('map_name', [
            'center' => [37.7749, -122.4194],
            'zoom' => 3,
            'tileLayer' => [
                'url' => 'https://foobar.org/{z}/{x}/{y}.png',
                'attribution' => '© OpenStreetMap contributors (kudos)',
                'options' => [
                    'maxZoom' => 19,
                ],
            ],
            'fitBoundsToMarkers' => true,
        ]);

        self::assertInstanceOf(Map::class, $map);
        self::assertSame('map_name', $map->getName());

        $view = $map->createView();
        self::assertSame(['lat' => 37.7749, 'lng' => -122.4194], $view['center']);
        self::assertSame(3.0, $view['zoom']);
        self::assertTrue($view['fitBoundsToMarkers']);

        self::assertEquals([
            'url' => 'https://foobar.org/{z}/{x}/{y}.png',
            'attribution' => '© OpenStreetMap contributors (kudos)',
            'maxZoom' => 19,
        ], $view['tileLayer']);
    }
}
