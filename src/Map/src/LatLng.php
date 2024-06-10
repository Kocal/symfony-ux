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

namespace Symfony\UX\Map;

use Symfony\UX\Map\Exception\InvalidArgumentException;

/**
 * Represents a geographical point.
 */
final class LatLng
{
    public function __construct(
        public readonly float $latitude,
        public readonly float $longitude,
    ) {
        if ($latitude < -90 || $latitude > 90) {
            throw new InvalidArgumentException(sprintf('Latitude must be between -90 and 90 degrees, "%s" given.', $latitude));
        }

        if ($longitude < -180 || $longitude > 180) {
            throw new InvalidArgumentException(sprintf('Longitude must be between -180 and 180 degrees, "%s" given.', $longitude));
        }
    }

    /**
     * @return array{latitude: float, longitude: float}
     */
    public function createView(): array
    {
        return [
            'lat' => $this->latitude,
            'lng' => $this->longitude,
        ];
    }
}
