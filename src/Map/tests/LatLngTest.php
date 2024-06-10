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

namespace Symfony\UX\Map\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\UX\Map\Exception\InvalidArgumentException;
use Symfony\UX\Map\LatLng;

class LatLngTest extends TestCase
{
    public static function provideInvalidLatLng(): iterable
    {
        yield [91, 0, 'Latitude must be between -90 and 90 degrees, "91" given.'];
        yield [-91, 0, 'Latitude must be between -90 and 90 degrees, "-91" given.'];
        yield [0, 181, 'Longitude must be between -180 and 180 degrees, "181" given.'];
        yield [0, -181, 'Longitude must be between -180 and 180 degrees, "-181" given.'];
    }

    /**
     * @dataProvider provideInvalidLatLng
     */
    public function testInvalidLatLng(float $latitude, float $longitude, string $expectedExceptionMessage): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedExceptionMessage);

        new LatLng($latitude, $longitude);
    }

    public function testLatLng(): void
    {
        $latLng = new LatLng(48.8566, 2.3522);

        self::assertSame(48.8566, $latLng->latitude);
        self::assertSame(2.3522, $latLng->longitude);
    }

    public function testCreateView(): void
    {
        $latLng = new LatLng(48.8566, 2.3533);

        self::assertSame(['lat' => 48.8566, 'lng' => 2.3533], $latLng->createView());
    }
}
