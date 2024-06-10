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
 * Options for the rendering of the zoom control.
 *
 * @see https://developers.google.com/maps/documentation/javascript/reference/control#ZoomControlOptions
 */
final class ZoomControlOptions
{
    public function __construct(
        public ControlPosition $position = ControlPosition::INLINE_END_BLOCK_END,
    ) {
    }

    public function createView(): array
    {
        return [
            'position' => $this->position->value,
        ];
    }
}
