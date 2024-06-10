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

namespace Symfony\UX\Map\Provider\GoogleMaps;

use Symfony\UX\Map\Factory\MapFactoryInterface;
use Symfony\UX\Map\LatLng;
use Symfony\UX\Map\MapInterface;

/**
 * Create a Google Maps map, with options.
 */
final class MapFactory implements MapFactoryInterface
{
    /**
     * @param array{
     *     mapId?: string,
     *     center?: array{float, float},
     *     zoom?: numeric,
     *     gestureHandling?: bool,
     *     backgroundColor?: string,
     *     enableDoubleClickZoom?: bool,
     *     zoomControl?: bool,
     *     mapTypeControl?: bool,
     *     streetViewControl?: bool,
     *     fullscreenControl?: bool,
     *     fitBoundsToMarkers?: bool,
     * } $options
     */
    public function createMap(string $name, array $options = []): MapInterface
    {
        $map = new Map($name);

        if ($options['mapId'] ?? null) {
            $map->setMapId($options['mapId']);
        }
        if ($options['center'] ?? null) {
            $map->setCenter(new LatLng($options['center'][0], $options['center'][1]));
        }
        if ($options['zoom'] ?? null) {
            $map->setZoom($options['zoom']);
        }
        if ($options['gestureHandling'] ?? null) {
            $map->setGestureHandling(GestureHandling::from($options['gestureHandling']));
        }
        if ($options['backgroundColor'] ?? null) {
            $map->setBackgroundColor($options['backgroundColor']);
        }
        if ($options['enableDoubleClickZoom'] ?? null) {
            $map->enableDoubleClickZoom($options['enableDoubleClickZoom']);
        }
        if ($options['zoomControl'] ?? null) {
            $map->enableZoomControl($options['zoomControl']);
        }
        if ($options['mapTypeControl'] ?? null) {
            $map->enableMapTypeControl($options['mapTypeControl']);
        }
        if ($options['streetViewControl'] ?? null) {
            $map->enableStreetViewControl($options['streetViewControl']);
        }
        if ($options['fullscreenControl'] ?? null) {
            $map->enableFullscreenControl($options['fullscreenControl']);
        }
        if ($options['fitBoundsToMarkers'] ?? null) {
            $map->enableFitBoundsToMarkers($options['fitBoundsToMarkers']);
        }

        return $map;
    }
}
