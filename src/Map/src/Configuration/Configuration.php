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

namespace Symfony\UX\Map\Configuration;

use Symfony\UX\Map\Exception\ConflictingMapProvidersOnSamePageException;
use Symfony\UX\Map\Exception\MapNotFoundException;
use Symfony\UX\Map\Exception\ProviderNotFoundException;

final class Configuration
{
    /** @var array<string, Map> */
    private readonly array $maps;

    /**
     * @param array<string, array<mixed>> $providersConfig
     * @param array<string, array<mixed>> $mapsConfig
     */
    public function __construct(
        array $providersConfig,
        array $mapsConfig,
    ) {
        $providers = [];
        foreach ($providersConfig as $providerName => $providerConfig) {
            $providers[$providerName] = new Provider(
                $providerName,
                $providerConfig['provider'],
                $providerConfig['options'] ?? [],
            );
        }

        $maps = [];
        foreach ($mapsConfig as $mapName => $mapConfig) {
            $maps[$mapName] = new Map(
                $mapName,
                $mapConfig['options'] ?? [],
                $providers[$mapConfig['provider']] ?? throw new ProviderNotFoundException($mapConfig['provider']),
            );
        }
        $this->maps = $maps;
    }

    public function getMap(string $mapName): Map
    {
        return $this->maps[$mapName] ?? throw new MapNotFoundException($mapName);
    }

    /**
     * @param array<string> $mapNames
     *
     * @throws ConflictingMapProvidersOnSamePageException if providers conflict with each other
     */
    public function validateSimultaneousMapsUsage(array $mapNames): void
    {
        $usedProviders = [];

        foreach ($mapNames as $mapName) {
            $map = $this->getMap($mapName);

            if (!\in_array($map->provider, $usedProviders, true)) {
                $usedProviders[] = $map->provider;
            }
        }

        foreach ($usedProviders as $provider) {
            $similarProviders = array_filter($usedProviders, fn (Provider $usedProvider) => $provider->provider === $usedProvider->provider && $provider !== $usedProvider);

            if ($similarProviders) {
                throw new ConflictingMapProvidersOnSamePageException($provider->name, array_map(fn (Provider $similarProvider) => $similarProvider->name, $similarProviders));
            }
        }
    }
}
