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

namespace Symfony\UX\Map\Provider\Leaflet;

use Symfony\UX\Map\Factory\MapFactoryInterface;
use Symfony\UX\Map\LatLng;
use Symfony\UX\Map\MapInterface;

/**
 * Creates a Leaflet map, with options.
 */
final class MapFactory implements MapFactoryInterface
{
    /**
     * @param array{
     *     center?: array{float, float},
     *     zoom?: numeric,
     *     tileLayer?: array{ url?: string, attribution?: string, options?: array<mixed> },
     *     fitBoundsToMarkers?: bool,
     * } $options
     */
    public function createMap(string $name, array $options = []): MapInterface
    {
        $tileLayer = array_merge([
            'url' => 'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
            'attribution' => '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            'options' => [],
        ], $options['tileLayer'] ?? []);

        $map = new Map(
            $name,
            tileLayer: new TileLayer($tileLayer['url'], $tileLayer['attribution'], $tileLayer['options'])
        );

        if ($options['center'] ?? null) {
            $map->setCenter(new LatLng($options['center'][0], $options['center'][1]));
        }
        if ($options['zoom'] ?? null) {
            $map->setZoom($options['zoom']);
        }
        if ($options['fitBoundsToMarkers'] ?? null) {
            $map->enableFitBoundsToMarkers($options['fitBoundsToMarkers']);
        }

        return $map;
    }
}
