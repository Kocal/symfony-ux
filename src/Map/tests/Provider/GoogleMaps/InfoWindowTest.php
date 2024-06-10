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
use Symfony\UX\Map\Exception\InvalidArgumentException;
use Symfony\UX\Map\LatLng;
use Symfony\UX\Map\Provider\GoogleMaps\InfoWindow;
use Symfony\UX\Map\Provider\GoogleMaps\Marker;

class InfoWindowTest extends TestCase
{
    public function testConstructShouldThrowIfNoPositionOrMarkerIsPassed(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('An "Symfony\UX\Map\Provider\GoogleMaps\InfoWindow" must be associated with a position or a marker.');

        new InfoWindow();
    }

    public function testMarkerPositionShouldTakePrecedenceOverPosition(): void
    {
        $infoWindow = new InfoWindow(
            marker: new Marker(position: new LatLng(1, 2)),
            position: new LatLng(3, 4)
        );

        $position = \Closure::bind(fn () => $infoWindow->position, $infoWindow, InfoWindow::class)();

        self::assertEquals(new LatLng(1, 2), $position);
    }

    public function testPositionFallbackToPositionIfNoMarkerIsPassed(): void
    {
        $infoWindow = new InfoWindow(
            position: new LatLng(3, 4)
        );

        $position = \Closure::bind(fn () => $infoWindow->position, $infoWindow, InfoWindow::class)();

        self::assertEquals(new LatLng(3, 4), $position);
    }

    public function testCreateView(): void
    {
        $infoWindow = new InfoWindow(
            position: new LatLng(3, 4)
        );

        self::assertEquals([
            'headerContent' => null,
            'content' => null,
            'position' => ['lat' => 3, 'lng' => 4],
            'opened' => false,
            'autoClose' => true,
            '_markerId' => null,
        ], $infoWindow->createView());
    }
}
