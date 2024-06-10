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

use Symfony\UX\Map\LatLng;
use Symfony\UX\Map\MapInterface;
use Symfony\UX\Map\MapTrait;

/**
 * Represents a Google Maps map.
 *
 * @see https://developers.google.com/maps/documentation/javascript/reference/map
 */
final class Map implements MapInterface
{
    use MapTrait;

    public static function getMainDataController(): string
    {
        return '@symfony/ux-map/google_maps';
    }

    /**
     * @param array<Marker>     $markers
     * @param array<InfoWindow> $infoWindows
     */
    public function __construct(
        private string $name,
        // Google Maps specific options
        private ?string $mapId = null,
        private ?LatLng $center = null,
        private ?float $zoom = null,
        private GestureHandling $gestureHandling = GestureHandling::Auto,
        private ?string $backgroundColor = null,
        private bool $disableDoubleClickZoom = false,
        private bool $zoomControl = true,
        private ZoomControlOptions $zoomControlOptions = new ZoomControlOptions(),
        private bool $mapTypeControl = true,
        private MapTypeControlOptions $mapTypeControlOptions = new MapTypeControlOptions(),
        private bool $streetViewControl = true,
        private StreetViewControlOptions $streetViewControlOptions = new StreetViewControlOptions(),
        private bool $fullscreenControl = true,
        private FullscreenControlOptions $fullscreenControlOptions = new FullscreenControlOptions(),
        // Custom options
        private bool $fitBoundsToMarkers = false,
        private array $markers = [],
        private array $infoWindows = [],
    ) {
    }

    public function setMapId(string $mapId): self
    {
        $this->mapId = $mapId;

        return $this;
    }

    public function setCenter(LatLng $center): self
    {
        $this->center = $center;

        return $this;
    }

    public function setZoom(float $zoom): self
    {
        $this->zoom = $zoom;

        return $this;
    }

    public function setGestureHandling(GestureHandling $gestureHandling): self
    {
        $this->gestureHandling = $gestureHandling;

        return $this;
    }

    public function setBackgroundColor(string $backgroundColor): self
    {
        $this->backgroundColor = $backgroundColor;

        return $this;
    }

    public function enableDoubleClickZoom(bool $enable = true): self
    {
        $this->disableDoubleClickZoom = !$enable;

        return $this;
    }

    public function enableZoomControl(bool $enable = true): self
    {
        $this->zoomControl = $enable;

        return $this;
    }

    public function setZoomControlOptions(ZoomControlOptions $zoomControlOptions): self
    {
        $this->zoomControlOptions = $zoomControlOptions;

        return $this;
    }

    public function enableMapTypeControl(bool $enable = true): self
    {
        $this->mapTypeControl = $enable;

        return $this;
    }

    public function setMapTypeControlOptions(MapTypeControlOptions $mapTypeControlOptions): self
    {
        $this->mapTypeControlOptions = $mapTypeControlOptions;

        return $this;
    }

    public function enableStreetViewControl(bool $enable = true): self
    {
        $this->streetViewControl = $enable;

        return $this;
    }

    public function setStreetViewControlOptions(StreetViewControlOptions $streetViewControlOptions): self
    {
        $this->streetViewControlOptions = $streetViewControlOptions;

        return $this;
    }

    public function enableFullscreenControl(bool $enable = true): self
    {
        $this->fullscreenControl = $enable;

        return $this;
    }

    public function setFullscreenControlOptions(FullscreenControlOptions $fullscreenControlOptions): self
    {
        $this->fullscreenControlOptions = $fullscreenControlOptions;

        return $this;
    }

    public function enableFitBoundsToMarkers(bool $enable = true): self
    {
        $this->fitBoundsToMarkers = $enable;

        return $this;
    }

    public function addMarker(Marker $marker): self
    {
        $this->markers[] = $marker;

        return $this;
    }

    public function addInfoWindow(InfoWindow $infoWindow): self
    {
        $this->infoWindows[] = $infoWindow;

        return $this;
    }

    public function createView(): array
    {
        return [
            'mapId' => $this->mapId,
            'center' => $this->center?->createView(),
            'zoom' => $this->zoom,
            'gestureHandling' => $this->gestureHandling->value,
            'backgroundColor' => $this->backgroundColor,
            'disableDoubleClickZoom' => $this->disableDoubleClickZoom,
            'zoomControl' => $this->zoomControl,
            'zoomControlOptions' => $this->zoomControlOptions->createView(),
            'mapTypeControl' => $this->mapTypeControl,
            'mapTypeControlOptions' => $this->mapTypeControlOptions->createView(),
            'streetViewControl' => $this->streetViewControl,
            'streetViewControlOptions' => $this->streetViewControlOptions->createView(),
            'fullscreenControl' => $this->fullscreenControl,
            'fullscreenControlOptions' => $this->fullscreenControlOptions->createView(),
            'fitBoundsToMarkers' => $this->fitBoundsToMarkers,
            'markers' => array_map(fn (Marker $marker) => $marker->createView(), $this->markers),
            'infoWindows' => array_map(fn (InfoWindow $infoWindow) => $infoWindow->createView(), $this->infoWindows),
        ];
    }
}
