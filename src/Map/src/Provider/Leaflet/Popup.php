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

use Symfony\UX\Map\Exception\InvalidArgumentException;
use Symfony\UX\Map\LatLng;

/**
 * Represents a popup on a Leaflet map.
 *
 * @see https://leafletjs.com/reference.html#popup
 */
final class Popup
{
    private readonly LatLng $position;

    public function __construct(
        private readonly string $content,
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
            '_markerId' => $this->marker?->getId(),
            'content' => $this->content,
            'position' => $this->position->createView(),
            'opened' => $this->opened,
            'autoClose' => $this->autoClose,
        ];
    }
}
