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

/**
 * Options for the rendering of the map type control.
 *
 * @see https://developers.google.com/maps/documentation/javascript/reference/control#MapTypeControlOptions
 */
final class MapTypeControlOptions
{
    /**
     * @param array<MapTypeId|string> $mapTypeIds
     */
    public function __construct(
        public array $mapTypeIds = [],
        public ControlPosition $position = ControlPosition::BLOCK_START_INLINE_START,
        public MapTypeControlStyle $style = MapTypeControlStyle::DEFAULT,
    ) {
    }

    public function createView(): array
    {
        return [
            'mapTypeIds' => array_map(
                fn (MapTypeId|string $mapTypeId) => $mapTypeId instanceof MapTypeId ? $mapTypeId->value : $mapTypeId,
                $this->mapTypeIds
            ),
            'position' => $this->position->value,
            'style' => $this->style->value,
        ];
    }
}
