<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\UX\Map\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\UX\Map\Tests\Kernel\FrameworkAppKernel;
use Symfony\UX\Map\Tests\Kernel\TwigAppKernel;

class UxMapBundleTest extends TestCase
{
    public static function provideKernels()
    {
        yield 'framework' => [new FrameworkAppKernel('test', true)];
        yield 'twig' => [new TwigAppKernel('test', true)];
    }

    /**
     * @dataProvider provideKernels
     */
    public function testBootKernel(Kernel $kernel)
    {
        $kernel->boot();
        self::assertArrayHasKey('UXMapBundle', $kernel->getBundles());
    }

    public function testMinimalConfigurationShouldNotThrow()
    {
        self::expectNotToPerformAssertions();

        $kernel = $this->createAndConfigureKernel(uxMapConfiguration: []);
        $kernel->boot();
    }

    public function testValidateSupportedProvider()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\InvalidConfigurationException::class);
        $this->expectExceptionMessage('The provider "foo" is not supported.');

        $kernel = $this->createAndConfigureKernel(uxMapConfiguration: [
            'providers' => [
                'foo' => [
                    'provider' => 'foo',
                ],
            ],
        ]);
        $kernel->boot();
    }

    public function testGoogleProviderValidation()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\InvalidConfigurationException::class);
        $this->expectExceptionMessage('Invalid configuration for path "ux_map.providers.google_maps": The "key" option is required for the "google_maps" provider.');

        $kernel = $this->createAndConfigureKernel(uxMapConfiguration: [
            'providers' => [
                'google_maps' => [
                    'provider' => 'google_maps',
                ],
            ],
        ]);
        $kernel->boot();
    }

    public function testMapProviderValidation()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The provider "google_map" for the map "google_map_1" is not found, has it been correctly registered?');

        $kernel = $this->createAndConfigureKernel(uxMapConfiguration: [
            'maps' => [
                'google_map_1' => [
                    'provider' => 'google_map',
                ],
            ],
        ]);
        $kernel->boot();
    }

    private function createAndConfigureKernel(array $uxMapConfiguration)
    {
        return new class('test', true, $uxMapConfiguration) extends TwigAppKernel {
            public function __construct(string $environment, bool $debug, private array $uxMapConfiguration)
            {
                parent::__construct($environment, $debug);
            }

            public function registerContainerConfiguration(LoaderInterface $loader)
            {
                parent::registerContainerConfiguration($loader);

                $loader->load(function (ContainerBuilder $container) {
                    $container->loadFromExtension('ux_map', $this->uxMapConfiguration);
                });
            }
        };
    }
}
