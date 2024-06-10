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

use Symfony\UX\Map\AssetMapper\ImportMap\Compiler\LeafletReplaceImagesAssetCompiler;
use Symfony\UX\Map\AssetMapper\ImportMap\Resolver\LeafletPackageResolver;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set('ux_map.asset_mapper.leaflet_replace_images_compiler', LeafletReplaceImagesAssetCompiler::class)
            ->args([
                service('logger'),
            ])
            ->tag('asset_mapper.compiler')
            ->tag('monolog.logger', ['channel' => 'asset_mapper'])

        ->set('ux_map.asset_mapper.importmap.resolver.leaflet_package_resolver', LeafletPackageResolver::class)
        ->args([
            service('.inner'),
            service('http_client')->nullOnInvalid(),
        ])
        ->decorate('asset_mapper.importmap.resolver')
    ;
};
