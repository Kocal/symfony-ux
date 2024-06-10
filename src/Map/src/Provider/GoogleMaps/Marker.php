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

/**
 * Represents a marker on a Google Maps map.
 *
 * @see https://developers.google.com/maps/documentation/javascript/reference/advanced-markers
 */
final class Marker
{
    private int $id;

    public function __construct(
        private readonly LatLng $position,
        private readonly ?string $title = null,
    ) {
        $this->id = spl_object_id($this);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPosition(): LatLng
    {
        return $this->position;
    }

    public function createView(): array
    {
        return [
            '_id' => $this->id,
            'position' => $this->position->createView(),
            'title' => $this->title,
        ];
    }
}
