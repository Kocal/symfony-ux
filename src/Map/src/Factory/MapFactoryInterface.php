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

namespace Symfony\UX\Map\Factory;

use Symfony\UX\Map\MapInterface;

interface MapFactoryInterface
{
    /**
     * @param array<string,mixed> $options
     */
    public function createMap(string $name, array $options = []): MapInterface;
}
