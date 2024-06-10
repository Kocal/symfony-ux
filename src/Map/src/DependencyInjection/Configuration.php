<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\UX\Map\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

/**
 * @author Hugo Alliaume <hugo@alliau.me>
 *
 * @experimental
 */
class Configuration implements ConfigurationInterface
{
    private const PROVIDERS = [
        'google_maps' => [
            'available_options' => [
                'mapId',
                'center',
                'zoom',
                'gestureHandling',
                'backgroundColor',
                'enableDoubleClickZoom',
                'zoomControl',
                'mapTypeControl',
                'streetViewControl',
                'fullscreenControl',
                'fitBoundsToMarkers',
            ],
        ],
        'leaflet' => [
            'available_options' => [
                'center',
                'zoom',
                'tileLayer',
                'fitBoundsToMarkers',
            ],
        ],
    ];

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('ux_map');
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->arrayNode('providers')
                    ->normalizeKeys(false)
                    ->useAttributeAsKey('name')
                    ->defaultValue([])
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('provider')
                                ->isRequired()
                                ->validate()
                                    ->ifNotInArray(array_keys(self::PROVIDERS))
                                    ->thenInvalid('The provider %s is not supported.')
                                ->end()
                            ->end()
                            ->arrayNode('options')
                                ->normalizeKeys(false)
                                ->defaultValue([])
                                ->prototype('variable')->end()
                            ->end()
                        ->end()
                        ->validate()
                            ->ifTrue(function ($v) { return 'google_maps' === $v['provider'] && !isset($v['options']['key']); })
                            ->thenInvalid('The "key" option is required for the "google_maps" provider.')
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('maps')
                    ->normalizeKeys(false)
                    ->useAttributeAsKey('name')
                    ->defaultValue([])
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('provider')->isRequired()->end()
                            ->arrayNode('options')
                                ->normalizeKeys(false)
                                ->defaultValue([])
                                ->prototype('variable')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()

            ->beforeNormalization()
                ->always(function ($v) {
                    // Validate that the provider exists
                    foreach ($v['maps'] ?? [] as $mapName => $map) {
                        if (!isset($v['providers'][$map['provider']])) {
                            throw new InvalidArgumentException(sprintf('The provider "%s" for the map "%s" is not found, has it been correctly registered?', $map['provider'], $mapName));
                        }
                    }

                    foreach ($v['maps'] ?? [] as $map) {
                        $this->validateMapOptions($map, $v['providers'][$map['provider']]);
                    }

                    return $v;
                })
            ->end()
        ;

        return $treeBuilder;
    }

    private function validateMapOptions(array $map, array $provider): void
    {
        $availableOptions = self::PROVIDERS[$provider['provider']]['available_options'] ?? [];
        $userOptions = array_keys($map['options'] ?? []);
        $invalidOptions = array_diff($userOptions, $availableOptions);

        foreach ($invalidOptions as $invalidOption) {
            $alternatives = [];
            foreach ($availableOptions as $availableOption) {
                $lev = levenshtein($invalidOption, $availableOption);
                if ($lev <= \strlen($invalidOption) / 3 || str_contains($availableOption, $invalidOption)) {
                    $alternatives[] = $availableOption;
                }
            }

            if ($alternatives) {
                throw new InvalidArgumentException(sprintf('The option "%s" is not supported for the provider "%s". Did you mean "%s"?', $invalidOption, $provider['provider'], implode('", "', $alternatives)));
            } else {
                throw new InvalidArgumentException(sprintf('The option "%s" is not supported for the provider "%s". Known options are "%s".', $invalidOption, $provider['provider'], implode('", "', $availableOptions)));
            }
        }
    }
}
