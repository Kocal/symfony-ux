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

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class MapExtension extends AbstractExtension
{
    public function getFunctions(): iterable
    {
        yield new TwigFunction('ux_map_script_tags', [MapRuntime::class, 'renderScriptTags'], ['is_safe' => ['html']]);
        yield new TwigFunction('render_map', [MapRuntime::class, 'renderMap'], ['is_safe' => ['html']]);
    }
}
