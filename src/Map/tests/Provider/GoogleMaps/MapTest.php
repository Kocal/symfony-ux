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
use Symfony\UX\Map\Provider\GoogleMaps\ControlPosition;
use Symfony\UX\Map\Provider\GoogleMaps\FullscreenControlOptions;
use Symfony\UX\Map\Provider\GoogleMaps\GestureHandling;
use Symfony\UX\Map\Provider\GoogleMaps\InfoWindow;
use Symfony\UX\Map\Provider\GoogleMaps\Map;
use Symfony\UX\Map\Provider\GoogleMaps\MapTypeControlOptions;
use Symfony\UX\Map\Provider\GoogleMaps\MapTypeControlStyle;
use Symfony\UX\Map\Provider\GoogleMaps\MapTypeId;
use Symfony\UX\Map\Provider\GoogleMaps\Marker;
use Symfony\UX\Map\Provider\GoogleMaps\StreetViewControlOptions;
use Symfony\UX\Map\Provider\GoogleMaps\ZoomControlOptions;

class MapTest extends TestCase
{
    public function testCreateViewWithDefaultOptions(): void
    {
        $map = new Map('map_name');

        self::assertEquals([
            'mapId' => null,
            'center' => null,
            'zoom' => null,
            'gestureHandling' => 'auto',
            'backgroundColor' => null,
            'disableDoubleClickZoom' => false,
            'zoomControl' => true,
            'zoomControlOptions' => [
                'position' => 22,
            ],
            'mapTypeControl' => true,
            'mapTypeControlOptions' => [
                'mapTypeIds' => [],
                'position' => 14,
                'style' => 0,
            ],
            'streetViewControl' => true,
            'streetViewControlOptions' => [
                'position' => 22,
            ],
            'fullscreenControl' => true,
            'fullscreenControlOptions' => [
                'position' => 20,
            ],
            'fitBoundsToMarkers' => false,
            'markers' => [],
            'infoWindows' => [],
        ], $map->createView());
    }

    public function testCreateViewWithCustomOptions(): void
    {
        $map = (new Map(
            name: 'map_name',
        ))
            ->setCenter(new LatLng(48.8566, 2.3522))
            ->setZoom(12)
            ->addMarker($paris = new Marker(position: new LatLng(48.8566, 2.3522), title: 'Paris'))
            ->addMarker($lyon = new Marker(position: new LatLng(45.7640, 4.8357), title: 'Lyon'))
            ->addMarker($marseille = new Marker(position: new LatLng(43.2965, 5.3698), title: 'Marseille'))
            ->addInfoWindow(new InfoWindow(
                headerContent: 'Paris',
                content: "Capitale de la France, est une grande ville européenne et un centre mondial de l'art, de la mode, de la gastronomie et de la culture.",
                marker: $paris,
            ))
            ->addInfoWindow(new InfoWindow(
                headerContent: 'Lyon',
                content: 'Ville française de la région historique Rhône-Alpes, se trouve à la jonction du Rhône et de la Saône.',
                marker: $lyon
            ))
            ->addInfoWindow(new InfoWindow(
                headerContent: 'Marseille',
                content: 'Ville portuaire du sud de la France, est une ville cosmopolite qui a été un centre d\'échanges commerciaux et culturels depuis sa fondation par les Grecs vers 600 av. J.-C.',
                marker: $marseille,
            ))
            ->addInfoWindow(new InfoWindow(
                headerContent: 'Strasbourg',
                content: 'Ville française située dans le Grand Est, est également le siège du Parlement européen.',
                position: new LatLng(48.5734, 7.7521),
                opened: true,
            ))
            ->setMapId('2b2d73ba4b8c7b41')
            ->setGestureHandling(GestureHandling::Greedy)
            ->setBackgroundColor('#f0f0f0')
            ->enableDoubleClickZoom(false)
            ->enableZoomControl(false)
            ->setZoomControlOptions(new ZoomControlOptions(
                position: ControlPosition::BLOCK_END_INLINE_END
            ))
            ->enableMapTypeControl(false)
            ->setMapTypeControlOptions(new MapTypeControlOptions(
                mapTypeIds: ['roadmap', 'satellite', MapTypeId::TERRAIN],
                position: ControlPosition::BLOCK_END_INLINE_START,
                style: MapTypeControlStyle::HORIZONTAL_BAR,
            ))
            ->enableStreetViewControl(false)
            ->setStreetViewControlOptions(new StreetViewControlOptions(
                position: ControlPosition::BLOCK_END_INLINE_START,
            ))
            ->enableFullscreenControl(false)
            ->setFullscreenControlOptions(new FullscreenControlOptions(
                position: ControlPosition::BLOCK_END_INLINE_START,
            ))
            ->enableFitBoundsToMarkers(false);

        self::assertEquals([
            'mapId' => '2b2d73ba4b8c7b41',
            'center' => ['lat' => 48.8566, 'lng' => 2.3522],
            'zoom' => 12.0,
            'gestureHandling' => 'greedy',
            'backgroundColor' => '#f0f0f0',
            'disableDoubleClickZoom' => true,
            'zoomControl' => false,
            'zoomControlOptions' => [
                'position' => ControlPosition::BLOCK_END_INLINE_END->value,
            ],
            'mapTypeControl' => false,
            'mapTypeControlOptions' => [
                'mapTypeIds' => ['roadmap', 'satellite', 'terrain'],
                'position' => ControlPosition::BLOCK_END_INLINE_START->value,
                'style' => 1,
            ],
            'streetViewControl' => false,
            'streetViewControlOptions' => [
                'position' => ControlPosition::BLOCK_END_INLINE_START->value,
            ],
            'fullscreenControl' => false,
            'fullscreenControlOptions' => [
                'position' => ControlPosition::BLOCK_END_INLINE_START->value,
            ],
            'fitBoundsToMarkers' => false,
            'markers' => [
                [
                    '_id' => $paris->getId(),
                    'position' => ['lat' => 48.8566, 'lng' => 2.3522],
                    'title' => 'Paris',
                ],
                [
                    '_id' => $lyon->getId(),
                    'position' => ['lat' => 45.764, 'lng' => 4.8357],
                    'title' => 'Lyon',
                ],
                [
                    '_id' => $marseille->getId(),
                    'position' => ['lat' => 43.2965, 'lng' => 5.3698],
                    'title' => 'Marseille',
                ],
            ],
            'infoWindows' => [
                [
                    '_markerId' => $paris->getId(),
                    'headerContent' => 'Paris',
                    'content' => "Capitale de la France, est une grande ville européenne et un centre mondial de l'art, de la mode, de la gastronomie et de la culture.",
                    'position' => ['lat' => 48.8566, 'lng' => 2.3522],
                    'opened' => false,
                    'autoClose' => true,
                ],
                [
                    '_markerId' => $lyon->getId(),
                    'headerContent' => 'Lyon',
                    'content' => 'Ville française de la région historique Rhône-Alpes, se trouve à la jonction du Rhône et de la Saône.',
                    'position' => ['lat' => 45.764, 'lng' => 4.8357],
                    'opened' => false,
                    'autoClose' => true,
                ],
                [
                    '_markerId' => $marseille->getId(),
                    'headerContent' => 'Marseille',
                    'content' => 'Ville portuaire du sud de la France, est une ville cosmopolite qui a été un centre d\'échanges commerciaux et culturels depuis sa fondation par les Grecs vers 600 av. J.-C.',
                    'position' => ['lat' => 43.2965, 'lng' => 5.3698],
                    'opened' => false,
                    'autoClose' => true,
                ],
                [
                    '_markerId' => null,
                    'headerContent' => 'Strasbourg',
                    'content' => 'Ville française située dans le Grand Est, est également le siège du Parlement européen.',
                    'position' => ['lat' => 48.5734, 'lng' => 7.7521],
                    'opened' => true,
                    'autoClose' => true,
                ],
            ],
        ], $map->createView());
    }
}
