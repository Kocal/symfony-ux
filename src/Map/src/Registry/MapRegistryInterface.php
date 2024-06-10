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

namespace Symfony\UX\Map\Registry;

use Symfony\UX\Map\MapInterface;

interface MapRegistryInterface
{
    public function register(MapInterface $map): void;

    /**
     * @return iterable<MapInterface>
     */
    public function all(): array;
}
