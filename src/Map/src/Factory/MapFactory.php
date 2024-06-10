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

use Psr\Container\ContainerInterface;
use Symfony\UX\Map\Configuration\Configuration;
use Symfony\UX\Map\Exception\ProviderNotFoundException;
use Symfony\UX\Map\MapInterface;
use Symfony\UX\Map\Registry\MapRegistryInterface;

/**
 * Creates a map based on the configuration, and registers it in the map registry.
 */
final class MapFactory implements MapFactoryInterface
{
    public function __construct(
        private ContainerInterface $mapFactories,
        private Configuration $configuration,
        private MapRegistryInterface $mapRegistry,
    ) {
    }

    public function createMap(string $name, array $options = []): MapInterface
    {
        $mapConfig = $this->configuration->getMap($name);

        if (!$this->mapFactories->has($mapConfig->provider->provider)) {
            throw new ProviderNotFoundException($mapConfig->provider->provider);
        }

        $mapFactory = $this->mapFactories->get($mapConfig->provider->provider);
        $map = $mapFactory->createMap($name, $options + $mapConfig->options);

        $this->mapRegistry->register($map);

        return $map;
    }
}
