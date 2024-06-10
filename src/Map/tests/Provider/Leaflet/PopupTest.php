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
use Symfony\UX\Map\Exception\InvalidArgumentException;
use Symfony\UX\Map\LatLng;
use Symfony\UX\Map\Provider\Leaflet\Marker;
use Symfony\UX\Map\Provider\Leaflet\Popup;

class PopupTest extends TestCase
{
    public function testConstructShouldThrowIfNoPositionOrMarkerIsPassed(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('An "Symfony\UX\Map\Provider\Leaflet\Popup" must be associated with a position or a marker.');

        new Popup(content: 'Hello!');
    }

    public function testMarkerPositionShouldTakePrecedenceOverPosition(): void
    {
        $Popup = new Popup(
            content: 'Hello!',
            marker: new Marker(position: new LatLng(1, 2)),
            position: new LatLng(3, 4)
        );

        $position = \Closure::bind(fn () => $Popup->position, $Popup, Popup::class)();

        self::assertEquals(new LatLng(1, 2), $position);
    }

    public function testPositionFallbackToPositionIfNoMarkerIsPassed(): void
    {
        $Popup = new Popup(
            content: 'Hello!',
            position: new LatLng(3, 4)
        );

        $position = \Closure::bind(fn () => $Popup->position, $Popup, Popup::class)();

        self::assertEquals(new LatLng(3, 4), $position);
    }

    public function testCreateView(): void
    {
        $Popup = new Popup(
            content: 'Hello!',
            position: new LatLng(3, 4)
        );

        self::assertEquals([
            'content' => 'Hello!',
            'position' => ['lat' => 3, 'lng' => 4],
            'opened' => false,
            'autoClose' => true,
            '_markerId' => null,
        ], $Popup->createView());
    }
}
