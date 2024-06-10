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

namespace Symfony\UX\Map\Tests\Factory;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\UX\Map\Configuration\Configuration;
use Symfony\UX\Map\Exception\MapNotFoundException;
use Symfony\UX\Map\Factory\MapFactory;
use Symfony\UX\Map\Factory\MapFactoryInterface;
use Symfony\UX\Map\MapInterface;
use Symfony\UX\Map\MapTrait;
use Symfony\UX\Map\Registry\MapRegistry;

final class MapFactoryTest extends TestCase
{
    private MapFactoryInterface $fakeFactory;
    private MapFactory $mapFactory;
    private MapRegistry $mapRegistry;

    protected function setUp(): void
    {
        $this->fakeFactory = new class() implements MapFactoryInterface {
            public string $passedName = '';
            public array $passedOptions = [];

            public function createMap(string $name, array $options = []): MapInterface
            {
                $this->passedName = $name;
                $this->passedOptions = $options;

                return new class() implements MapInterface {
                    use MapTrait;

                    public static function getMainDataController(): string
                    {
                        throw new \BadMethodCallException('Not implemented');
                    }

                    public function createView(): array
                    {
                        throw new \BadMethodCallException('Not implemented');
                    }
                };
            }
        };

        $this->mapFactory = new MapFactory(
            new ServiceLocator([
                'leaflet' => fn () => $this->fakeFactory,
                'google_maps' => fn () => $this->fakeFactory,
            ]),
            new Configuration([
                'leaflet' => ['provider' => 'leaflet'],
                'google_maps' => ['provider' => 'google_maps'],
            ], [
                'map_1' => [
                    'provider' => 'leaflet',
                ],
                'map_2' => [
                    'provider' => 'google_maps',
                ],
                'map_3' => [
                    'provider' => 'google_maps',
                    'options' => [
                        'mapId' => 'abcdefgh1234567890',
                    ],
                ],
            ]),
            $this->mapRegistry = new MapRegistry(),
        );

        self::assertEmpty($this->mapRegistry->all());
    }

    public function testCreateMap(): void
    {
        $map = $this->mapFactory->createMap('map_1');

        self::assertSame('map_1', $this->fakeFactory->passedName);
        self::assertSame([], $this->fakeFactory->passedOptions);
        self::assertContains($map, $this->mapRegistry->all());
    }

    public function testCreateMapWithCustomOptions(): void
    {
        $map = $this->mapFactory->createMap('map_3', ['zoom' => 10]);

        self::assertSame('map_3', $this->fakeFactory->passedName);
        self::assertSame(['zoom' => 10, 'mapId' => 'abcdefgh1234567890'], $this->fakeFactory->passedOptions);
        self::assertContains($map, $this->mapRegistry->all());
    }

    public function testCreateMapWithInvalidMap(): void
    {
        $this->expectException(MapNotFoundException::class);
        $this->expectExceptionMessage('Map "foo_bar" is not found, has it been correctly configured?');

        $this->mapFactory->createMap('foo_bar');
    }
}
