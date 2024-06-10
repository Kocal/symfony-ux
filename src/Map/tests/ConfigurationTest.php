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
use Symfony\UX\Map\Configuration\Configuration;
use Symfony\UX\Map\Exception\MapNotFoundException;

final class ConfigurationTest extends TestCase
{
    private Configuration $configuration;

    protected function setUp(): void
    {
        $this->configuration = new Configuration(
            [
                'google_maps_provider' => [
                    'provider' => 'google_maps',
                    'options' => [
                        'key' => 'my_key',
                    ],
                ],
            ],
            [
                'google_maps_map_1' => [
                    'provider' => 'google_maps_provider',
                    'options' => [
                        'map_id' => 'DEMO_MAP_ID',
                    ],
                ],
            ]
        );
    }

    public function testGetMap(): void
    {
        $config = $this->configuration->getMap('google_maps_map_1');

        self::assertSame('google_maps_map_1', $config->name);
        self::assertEquals(['map_id' => 'DEMO_MAP_ID'], $config->options);

        self::assertSame('google_maps', $config->provider->provider);
        self::assertSame('google_maps_provider', $config->provider->name);
        self::assertEquals(['key' => 'my_key'], $config->provider->options);
    }

    public function testGetMapConfigWithUnknownMap(): void
    {
        $this->expectException(MapNotFoundException::class);
        $this->expectExceptionMessage('Map "unknown_map" is not found, has it been correctly configured?');

        $this->configuration->getMap('unknown_map');
    }
}
