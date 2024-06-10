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
use Symfony\UX\Map\LatLng;
use Symfony\UX\Map\Provider\GoogleMaps\Marker;

class MarkerTest extends TestCase
{
    public function testCreateView(): void
    {
        $marker = new Marker(
            new LatLng(48.8566, 2.3522),
            'Paris'
        );

        self::assertSame([
            '_id' => $marker->getId(),
            'position' => [
                'lat' => 48.8566,
                'lng' => 2.3522,
            ],
            'title' => 'Paris',
        ], $marker->createView());
    }
}
