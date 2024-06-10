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

namespace Symfony\UX\Map;

trait MapTrait
{
    private string $name;

    /**
     * @var array<string, mixed>
     */
    private $attributes = [];

    public function getName(): string
    {
        return $this->name;
    }

    public function setAttributes(array $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function getDataController(): ?string
    {
        return $this->attributes['data-controller'] ?? null;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
