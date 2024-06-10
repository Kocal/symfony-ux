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

namespace Symfony\UX\Map\Tests\Provider\Leaflet;

use PHPUnit\Framework\TestCase;
use Symfony\UX\Map\LatLng;
use Symfony\UX\Map\Provider\Leaflet;
use Symfony\UX\Map\Provider\Leaflet\Map;
use Symfony\UX\Map\Provider\Leaflet\Marker;
use Symfony\UX\Map\Provider\Leaflet\TileLayer;

class MapTest extends TestCase
{
    public function testCreateViewWithDefaultOptions(): void
    {
        $map = new Map(
            'map_name',
            new TileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>')
        );

        self::assertEquals([
            'center' => null,
            'zoom' => null,
            'fitBoundsToMarkers' => false,
            'markers' => [],
            'popups' => [],
            'tileLayer' => [
                'url' => 'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
                'attribution' => '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            ],
        ], $map->createView());
    }

    public function testCreateViewWithCustomOptions(): void
    {
        $map = (new Map(
            name: 'map_name',
            tileLayer: new TileLayer(
                url: 'https://foobar.org/{z}/{x}/{y}.png',
                attribution: '© OpenStreetMap contributors (kudos)',
                options: ['maxZoom' => 19]
            )
        ))
            ->setCenter(new LatLng(48.8566, 2.3522))
            ->setZoom(12)
            ->addMarker($paris = new Marker(position: new LatLng(48.8566, 2.3522), title: 'Paris'))
            ->addMarker($lyon = new Marker(position: new LatLng(45.7640, 4.8357), title: 'Lyon'))
            ->addMarker($marseille = new Marker(position: new LatLng(43.2965, 5.3698), title: 'Marseille'))
            ->addPopup(new Leaflet\Popup(
                content: "<b>Paris</b>, capitale de la France, est une grande ville européenne et un centre mondial de l'art, de la mode, de la gastronomie et de la culture.",
                marker: $paris,
            ))
            ->addPopup(new Leaflet\Popup(
                content: '<b>Lyon</b>, ville française de la région historique Rhône-Alpes, se trouve à la jonction du Rhône et de la Saône.',
                marker: $lyon
            ))
            ->addPopup(new Leaflet\Popup(
                content: '<b>Marseille</b>, ville portuaire du sud de la France, est une ville cosmopolite qui a été un centre d\'échanges commerciaux et culturels depuis sa fondation par les Grecs vers 600 av. J.-C.',
                marker: $marseille,
            ))
            ->addPopup(new Leaflet\Popup(
                content: '<b>Strasbourg</b>, ville française située dans le Grand Est, est également le siège du Parlement européen.',
                position: new LatLng(48.5734, 7.7521),
                opened: true,
            ))
            ->enableFitBoundsToMarkers(false);

        self::assertEquals([
            'center' => ['lat' => 48.8566, 'lng' => 2.3522],
            'zoom' => 12.0,
            'fitBoundsToMarkers' => false,
            'tileLayer' => [
                'url' => 'https://foobar.org/{z}/{x}/{y}.png',
                'attribution' => '© OpenStreetMap contributors (kudos)',
                'maxZoom' => 19,
            ],
            'markers' => [
                [
                    '_id' => $paris->getId(),
                    'position' => ['lat' => 48.8566, 'lng' => 2.3522],
                    'title' => 'Paris',
                    'riseOnHover' => false,
                    'riseOffset' => 250,
                    'draggable' => false,
                ],
                [
                    '_id' => $lyon->getId(),
                    'position' => ['lat' => 45.764, 'lng' => 4.8357],
                    'title' => 'Lyon',
                    'riseOnHover' => false,
                    'riseOffset' => 250,
                    'draggable' => false,
                ],
                [
                    '_id' => $marseille->getId(),
                    'position' => ['lat' => 43.2965, 'lng' => 5.3698],
                    'title' => 'Marseille',
                    'riseOnHover' => false,
                    'riseOffset' => 250,
                    'draggable' => false,
                ],
            ],
            'popups' => [
                [
                    '_markerId' => $paris->getId(),
                    'content' => "<b>Paris</b>, capitale de la France, est une grande ville européenne et un centre mondial de l'art, de la mode, de la gastronomie et de la culture.",
                    'position' => ['lat' => 48.8566, 'lng' => 2.3522],
                    'opened' => false,
                    'autoClose' => true,
                ],
                [
                    '_markerId' => $lyon->getId(),
                    'content' => '<b>Lyon</b>, ville française de la région historique Rhône-Alpes, se trouve à la jonction du Rhône et de la Saône.',
                    'position' => ['lat' => 45.764, 'lng' => 4.8357],
                    'opened' => false,
                    'autoClose' => true,
                ],
                [
                    '_markerId' => $marseille->getId(),
                    'content' => '<b>Marseille</b>, ville portuaire du sud de la France, est une ville cosmopolite qui a été un centre d\'échanges commerciaux et culturels depuis sa fondation par les Grecs vers 600 av. J.-C.',
                    'position' => ['lat' => 43.2965, 'lng' => 5.3698],
                    'opened' => false,
                    'autoClose' => true,
                ],
                [
                    '_markerId' => null,
                    'content' => '<b>Strasbourg</b>, ville française située dans le Grand Est, est également le siège du Parlement européen.',
                    'position' => ['lat' => 48.5734, 'lng' => 7.7521],
                    'opened' => true,
                    'autoClose' => true,
                ],
            ],
        ], $map->createView());
    }
}
