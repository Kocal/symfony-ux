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

namespace Symfony\UX\Map\Twig;

use Symfony\UX\Map\Configuration\Configuration;
use Symfony\UX\Map\MapInterface;
use Symfony\UX\Map\Registry\MapRegistryInterface;
use Symfony\UX\StimulusBundle\Helper\StimulusHelper;
use Twig\Extension\RuntimeExtensionInterface;

final class MapRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private readonly StimulusHelper $stimulus,
        private readonly MapRegistryInterface $mapRegistry,
        private readonly Configuration $configuration,
    ) {
    }

    public function renderScriptTags(): string
    {
        if (!$maps = $this->mapRegistry->all()) {
            return '';
        }

        $this->configuration->validateSimultaneousMapsUsage(array_map(fn (MapInterface $map) => $map->getName(), $maps));

        $scriptTags = [];

        $jsConfig = ['providers' => []];
        foreach ($maps as $map) {
            $mapConfig = $this->configuration->getMap($map->getName());

            $jsConfig['providers'][$mapConfig->provider->provider] = (object) $mapConfig->provider->options;
        }
        $scriptTags[] = sprintf('<script>window.__symfony_ux_maps = %s</script>', json_encode($jsConfig, flags: \JSON_THROW_ON_ERROR));

        return implode("\n", $scriptTags);
    }

    public function renderMap(MapInterface $map, array $attributes = []): string
    {
        $map->setAttributes($attributes + $map->getAttributes());

        $controllers = [];
        if ($map->getDataController()) {
            $controllers[$map->getDataController()] = [];
        }
        $controllers[$map::getMainDataController()] = ['view' => $map->createView()];

        $stimulusAttributes = $this->stimulus->createStimulusAttributes();
        foreach ($controllers as $name => $controllerValues) {
            $stimulusAttributes->addController($name, $controllerValues);
        }

        foreach ($map->getAttributes() as $name => $value) {
            if ('data-controller' === $name) {
                continue;
            }

            if (true === $value) {
                $stimulusAttributes->addAttribute($name, $name);
            } elseif (false !== $value) {
                $stimulusAttributes->addAttribute($name, $value);
            }
        }

        return sprintf('<div %s></div>', $stimulusAttributes);
    }
}
