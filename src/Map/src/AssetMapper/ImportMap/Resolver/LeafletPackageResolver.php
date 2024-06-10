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

namespace Symfony\UX\Map\AssetMapper\ImportMap\Resolver;

use Symfony\Component\AssetMapper\ImportMap\Resolver\PackageResolverInterface;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\UX\Map\AssetMapper\ImportMap\Compiler\LeafletReplaceImagesAssetCompiler;

/**
 * PackageResolver decorator for Leaflet.
 *
 * Some files mentioned in Leaflet's JavaScript code could not be detected as extra files by the actual PackageResolver.
 * Without this decorator, in the following code, the .png files won't be detected as extra files:
 *
 *      // ...
 *      $i.extend({options:{iconUrl:"marker-icon.png",iconRetinaUrl:"marker-icon-2x.png",shadowUrl:"marker-shadow.png",
 *      // ...
 */
class LeafletPackageResolver implements PackageResolverInterface
{
    public function __construct(
        private PackageResolverInterface $inner,
        private ?HttpClientInterface $httpClient,
    ) {
    }

    public function resolvePackages(array $packagesToRequire): array
    {
        return $this->inner->resolvePackages($packagesToRequire);
    }

    public function downloadPackages(array $importMapEntries, ?callable $progressCallback = null): array
    {
        $contents = $this->inner->downloadPackages($importMapEntries, $progressCallback);

        if (isset($contents['leaflet'])) {
            $this->httpClient ??= HttpClient::create();
            $responses = [];

            preg_match_all(LeafletReplaceImagesAssetCompiler::ASSETS_PATTERN, $contents['leaflet']['content'], $leafletAssets);

            foreach ($leafletAssets['asset'] as $leafletAsset) {
                $distPath = Path::join('dist', 'images', $leafletAsset);
                $responses[] = $this->httpClient->request(
                    'GET',
                    sprintf('https://cdn.jsdelivr.net/npm/leaflet@%s/%s', $importMapEntries['leaflet']->version, $distPath),
                    ['user_data' => ['dist_path' => $distPath]]
                );
            }

            foreach ($responses as $response) {
                $distPath = $response->getInfo('user_data')['dist_path'];
                $contents['leaflet']['extraFiles'][$distPath] = $response->getContent();
            }
        }

        return $contents;
    }
}
