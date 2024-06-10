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

use Symfony\UX\Map\LatLng;
use Symfony\UX\Map\MapInterface;
use Symfony\UX\Map\MapTrait;

/**
 * Represents a Leaflet map.
 *
 * @see https://leafletjs.com/reference.html#map
 */
final class Map implements MapInterface
{
    use MapTrait;

    public static function getMainDataController(): string
    {
        return '@symfony/ux-map/leaflet';
    }

    /**
     * @param array<Marker> $markers
     * @param array<Popup>  $popups
     */
    public function __construct(
        private string $name,
        // Leaflet options
        private TileLayer $tileLayer,
        private ?LatLng $center = null,
        private ?float $zoom = null,
        // Custom options
        private bool $fitBoundsToMarkers = false,
        private array $markers = [],
        private array $popups = [],
    ) {
    }

    public function setCenter(?LatLng $center): self
    {
        $this->center = $center;

        return $this;
    }

    public function setZoom(?float $zoom): self
    {
        $this->zoom = $zoom;

        return $this;
    }

    public function setTileLayer(TileLayer $tileLayer): self
    {
        $this->tileLayer = $tileLayer;

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

    public function addPopup(Popup $popup): self
    {
        $this->popups[] = $popup;

        return $this;
    }

    public function createView(): array
    {
        return [
            'center' => $this->center?->createView(),
            'zoom' => $this->zoom,
            'tileLayer' => $this->tileLayer?->createView(),
            'fitBoundsToMarkers' => $this->fitBoundsToMarkers,
            'markers' => array_map(fn (Marker $marker) => $marker->createView(), $this->markers),
            'popups' => array_map(fn (Popup $popup) => $popup->createView(), $this->popups),
        ];
    }
}
