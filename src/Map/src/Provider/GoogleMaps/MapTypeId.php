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
 * Identifiers for common MapTypes.
 *
 * @see https://developers.google.com/maps/documentation/javascript/reference/map#MapTypeId
 */
enum MapTypeId: string
{
    /**
     * This map type displays a transparent layer of major streets on satellite images.
     */
    case HYBRID = 'hybrid';

    /**
     * This map type displays a normal street map.
     */
    case ROADMAP = 'roadmap';

    /**
     * This map type displays satellite images.
     */
    case SATELLITE = 'satellite';

    /**
     * This map type displays maps with physical features such as terrain and vegetation.
     */
    case TERRAIN = 'terrain';
}
