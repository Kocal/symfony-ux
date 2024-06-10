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

namespace Symfony\UX\Map\Exception;

class MapNotFoundException extends \InvalidArgumentException implements Exception
{
    public function __construct(string $name)
    {
        parent::__construct(sprintf('Map "%s" is not found, has it been correctly configured?', $name));
    }
}
