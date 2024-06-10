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
use Symfony\UX\Map\Provider\GoogleMaps\Map;
use Symfony\UX\Map\Provider\GoogleMaps\MapFactory;

class MapFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $mapFactory = new MapFactory();

        $map = $mapFactory->createMap('map_name');

        self::assertInstanceOf(Map::class, $map);
        self::assertSame('map_name', $map->getName());
    }

    public function testCreateWithCustomOptions(): void
    {
        $mapFactory = new MapFactory();

        $map = $mapFactory->createMap('map_name', [
            'mapId' => 'DEMO_MAP_ID',
            'center' => [37.7749, -122.4194],
            'zoom' => 3,
            'gestureHandling' => 'auto',
            'backgroundColor' => '#f8f9fa',
            'enableDoubleClickZoom' => true,
            'zoomControl' => true,
            'mapTypeControl' => true,
            'streetViewControl' => true,
            'fullscreenControl' => true,
            'fitBoundsToMarkers' => true,
        ]);

        self::assertInstanceOf(Map::class, $map);
        self::assertSame('map_name', $map->getName());

        $view = $map->createView();

        self::assertSame('DEMO_MAP_ID', $view['mapId']);
        self::assertSame(['lat' => 37.7749, 'lng' => -122.4194], $view['center']);
        self::assertSame(3.0, $view['zoom']);
        self::assertSame(GestureHandling::Auto->value, $view['gestureHandling']);
        self::assertSame('#f8f9fa', $view['backgroundColor']);
        self::assertFalse($view['disableDoubleClickZoom']);
        self::assertTrue($view['zoomControl']);
        self::assertTrue($view['mapTypeControl']);
        self::assertTrue($view['streetViewControl']);
        self::assertTrue($view['fullscreenControl']);
        self::assertTrue($view['fitBoundsToMarkers']);
    }
}
