<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

/*
 * @author Hugo Alliaume <hugo@alliau.me>
 */

use Symfony\UX\Map\Configuration\Configuration;
use Symfony\UX\Map\Factory\MapFactory;
use Symfony\UX\Map\Factory\MapFactoryInterface;
use Symfony\UX\Map\Registry\MapRegistry;
use Symfony\UX\Map\Registry\MapRegistryInterface;
use Symfony\UX\Map\Twig\MapExtension;
use Symfony\UX\Map\Twig\MapRuntime;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set('ux_map.configuration', Configuration::class)
        ->args([
            param('ux_map.config.providers'),
            param('ux_map.config.maps'),
        ])

        ->set('ux_map.map_factory', MapFactory::class)
            ->args([
                tagged_locator('ux_map.map_factory', 'name'),
                service('ux_map.configuration'),
                service('ux_map.map_registry'),
            ])
        ->alias(MapFactoryInterface::class, 'ux_map.map_factory')

        ->set('ux_map.google_maps.map_factory', \Symfony\UX\Map\Provider\GoogleMaps\MapFactory::class)
            ->tag('ux_map.map_factory', ['name' => 'google_maps'])

        ->set('ux_map.leaflet.map_factory', \Symfony\UX\Map\Provider\Leaflet\MapFactory::class)
            ->tag('ux_map.map_factory', ['name' => 'leaflet'])

        ->set('ux_map.map_registry', MapRegistry::class)
        ->alias(MapRegistryInterface::class, 'ux_map.map_registry')

        ->set('ux_map.twig_extension', MapExtension::class)
            ->tag('twig.extension')

        ->set('ux_map.twig_runtime', MapRuntime::class)
            ->args([
                service('stimulus.helper'),
                service('ux_map.map_registry'),
                service('ux_map.configuration'),
            ])
            ->tag('twig.runtime')
    ;
};
