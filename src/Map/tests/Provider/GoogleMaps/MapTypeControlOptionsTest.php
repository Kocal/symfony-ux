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
use Symfony\UX\Map\Provider\GoogleMaps\MapTypeControlOptions;
use Symfony\UX\Map\Provider\GoogleMaps\MapTypeControlStyle;
use Symfony\UX\Map\Provider\GoogleMaps\MapTypeId;

class MapTypeControlOptionsTest extends TestCase
{
    public function testCreateView(): void
    {
        $options = new MapTypeControlOptions(
            mapTypeIds: [MapTypeId::SATELLITE, 'hybrid'],
            position: ControlPosition::BLOCK_END_INLINE_END,
            style: MapTypeControlStyle::HORIZONTAL_BAR,
        );

        self::assertSame([
            'mapTypeIds' => ['satellite', 'hybrid'],
            'position' => ControlPosition::BLOCK_END_INLINE_END->value,
            'style' => MapTypeControlStyle::HORIZONTAL_BAR->value,
        ], $options->createView());
    }
}
