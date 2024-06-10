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

final class Provider
{
    /**
     * @param array<string,mixed> $options
     */
    public function __construct(
        public readonly string $name,
        public readonly string $provider,
        public readonly array $options,
    ) {
    }
}
