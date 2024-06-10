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

namespace Symfony\UX\Map\AssetMapper\ImportMap\Compiler;

use Psr\Log\LoggerInterface;
use Symfony\Component\AssetMapper\AssetMapperInterface;
use Symfony\Component\AssetMapper\Compiler\AssetCompilerInterface;
use Symfony\Component\AssetMapper\Exception\RuntimeException;
use Symfony\Component\AssetMapper\MappedAsset;
use Symfony\Component\Filesystem\Path;

/**
 * Replaces the image paths in the Leaflet library JavaScript code with their public path.
 */
final class LeafletReplaceImagesAssetCompiler implements AssetCompilerInterface
{
    /**
     * https://regex101.com/r/n3fSEN/1.
     */
    public const ASSETS_PATTERN = '/"(?P<asset>[^"]+?\.png)"/';

    public function __construct(
        private readonly ?LoggerInterface $logger = null,
    ) {
    }

    public function supports(MappedAsset $asset): bool
    {
        return str_ends_with($asset->sourcePath, 'vendor/leaflet/leaflet.index.js');
    }

    public function compile(string $content, MappedAsset $asset, AssetMapperInterface $assetMapper): string
    {
        return preg_replace_callback(self::ASSETS_PATTERN, function ($matches) use ($asset, $assetMapper) {
            try {
                $resolvedSourcePath = Path::join(\dirname($asset->sourcePath), 'dist', 'images', $matches['asset']);
            } catch (RuntimeException $e) {
                $this->logger?->warning(sprintf('Error processing import in "%s": ', $asset->sourcePath).$e->getMessage());

                return $matches[0];
            }

            $dependentAsset = $assetMapper->getAssetFromSourcePath($resolvedSourcePath);

            if (null === $dependentAsset) {
                return $matches[0];
            }

            $asset->addDependency($dependentAsset);
            $relativePath = $dependentAsset->publicPath;

            return "\"$relativePath\"";
        }, $content);
    }
}
