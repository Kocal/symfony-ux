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

class ConflictingMapProvidersOnSamePageException extends RuntimeException implements Exception
{
    public function __construct(string $providerName, array $similarProvidersName)
    {
        parent::__construct(sprintf(
            'You cannot use the "%s" map provider on the same page as the following map providers: "%s", as their configuration will conflicts with each-other.',
            $providerName,
            implode('", "', $similarProvidersName)
        ));
    }
}
