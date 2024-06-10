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

use Symfony\Contracts\Service\ResetInterface;
use Symfony\UX\Map\MapInterface;

final class MapRegistry implements MapRegistryInterface, ResetInterface
{
    /**
     * @var array<MapInterface>
     */
    private array $maps = [];

    public function register(MapInterface $map): void
    {
        $this->maps[] = $map;
    }

    public function all(): array
    {
        return $this->maps;
    }

    public function reset(): void
    {
        $this->maps = [];
    }
}
