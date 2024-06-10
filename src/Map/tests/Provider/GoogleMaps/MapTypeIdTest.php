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
use Symfony\UX\Map\Provider\GoogleMaps\MapTypeId;

class MapTypeIdTest extends TestCase
{
    public function testEnumValues(): void
    {
        self::assertSame('hybrid', MapTypeId::HYBRID->value);
        self::assertSame('roadmap', MapTypeId::ROADMAP->value);
        self::assertSame('satellite', MapTypeId::SATELLITE->value);
        self::assertSame('terrain', MapTypeId::TERRAIN->value);
    }
}
