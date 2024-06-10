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
use Symfony\UX\Map\Provider\GoogleMaps\StreetViewControlOptions;

class StreetViewControlOptionsTest extends TestCase
{
    public function testCreateView(): void
    {
        $options = new StreetViewControlOptions(
            position: ControlPosition::INLINE_END_BLOCK_CENTER
        );

        self::assertSame([
            'position' => ControlPosition::INLINE_END_BLOCK_CENTER->value,
        ], $options->createView());
    }
}
