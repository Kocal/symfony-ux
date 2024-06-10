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

/**
 * Represents a tile layer for a Leaflet map.
 *
 * @see https://leafletjs.com/reference.html#tilelayer
 */
final class TileLayer
{
    public function __construct(
        public string $url,
        public string $attribution,
        public array $options = [],
    ) {
    }

    public function createView(): array
    {
        return [
            ...$this->options,
            'url' => $this->url,
            'attribution' => $this->attribution,
        ];
    }
}
