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

use Symfony\UX\Map\Exception\InvalidArgumentException;
use Symfony\UX\Map\LatLng;

/**
 * Represents an info window on a Google Maps map.
 *
 * @see https://developers.google.com/maps/documentation/javascript/reference/info-window
 */
final class InfoWindow
{
    private readonly LatLng $position;

    public function __construct(
        private readonly ?string $headerContent = null,
        private readonly ?string $content = null,
        ?LatLng $position = null,
        private readonly ?Marker $marker = null,
        private readonly bool $opened = false,
        private readonly bool $autoClose = true,
    ) {
        if (null === $position && null === $marker) {
            throw new InvalidArgumentException(sprintf('An "%s" must be associated with a position or a marker.', __CLASS__));
        }

        $this->position = $this->marker?->getPosition() ?? $position;
    }

    public function createView(): array
    {
        return [
            'headerContent' => $this->headerContent,
            'content' => $this->content,
            'position' => $this->position->createView(),
            'opened' => $this->opened,
            'autoClose' => $this->autoClose,
            '_markerId' => $this->marker?->getId(),
        ];
    }
}
