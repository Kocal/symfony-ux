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

namespace Symfony\UX\Map\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\UX\Map\Exception\ConflictingMapProvidersOnSamePageException;
use Symfony\UX\Map\Tests\Kernel\TwigAppKernel;

final class TwigTest extends KernelTestCase
{
    protected static function createKernel(array $options = []): KernelInterface
    {
        return new class('test', true) extends TwigAppKernel {
            public function registerContainerConfiguration(LoaderInterface $loader)
            {
                parent::registerContainerConfiguration($loader);

                $loader->load(function (ContainerBuilder $container) {
                    $container->loadFromExtension('ux_map', [
                        'providers' => [
                            'google_maps' => [
                                'provider' => 'google_maps',
                                'options' => [
                                    'key' => 'GOOGLE_MAPS_API_KEY',
                                ],
                            ],
                            'google_maps_2' => [
                                'provider' => 'google_maps',
                                'options' => [
                                    'key' => 'GOOGLE_MAPS_API_KEY_2',
                                ],
                            ],
                            'leaflet' => [
                                'provider' => 'leaflet',
                            ],
                        ],
                        'maps' => [
                            'google_maps_map_1' => [
                                'provider' => 'google_maps',
                            ],
                            'google_maps_map_2' => [
                                'provider' => 'google_maps',
                            ],
                            'google_maps_with_another_google_maps_provider' => [
                                'provider' => 'google_maps_2',
                            ],
                            'leaflet_map_1' => [
                                'provider' => 'leaflet',
                            ],
                        ],
                    ]);
                });
            }
        };
    }

    protected function setUp(): void
    {
        self::bootKernel();
    }

    public function testRenderScriptTagsShouldOutputNothingIfNoMapsHaveBeenCreated(): void
    {
        $twig = self::getContainer()->get('twig');
        $template = $twig->createTemplate('{{ ux_map_script_tags() }}');

        self::assertEmpty($template->render());
    }

    public function testRenderScriptTagsShouldOutputTheScriptTagsForTheMaps(): void
    {
        $twig = self::getContainer()->get('twig');
        $mapFactory = self::getContainer()->get('ux_map.map_factory');

        $mapFactory->createMap('google_maps_map_1');

        self::assertStringContainsString(
            '<script>window.__symfony_ux_maps = {"providers":{"google_maps":{"key":"GOOGLE_MAPS_API_KEY"}}}</script>',
            $twig->createTemplate('{{ ux_map_script_tags() }}')->render()
        );

        $mapFactory->createMap('google_maps_map_2');

        self::assertStringContainsString(
            '<script>window.__symfony_ux_maps = {"providers":{"google_maps":{"key":"GOOGLE_MAPS_API_KEY"}}}</script>',
            $twig->createTemplate('{{ ux_map_script_tags() }}')->render()
        );

        $mapFactory->createMap('leaflet_map_1');

        self::assertStringContainsString(
            '<script>window.__symfony_ux_maps = {"providers":{"google_maps":{"key":"GOOGLE_MAPS_API_KEY"},"leaflet":{}}}</script>',
            $twig->createTemplate('{{ ux_map_script_tags() }}')->render()
        );
    }

    public function testRenderScriptTagsShouldFailIfWeHaveTheSameKindOfProviderOnThePage(): void
    {
        $twig = self::getContainer()->get('twig');
        $mapFactory = self::getContainer()->get('ux_map.map_factory');

        $mapFactory->createMap('google_maps_map_1');
        $mapFactory->createMap('google_maps_with_another_google_maps_provider');

        try {
            $twig->createTemplate('{{ ux_map_script_tags() }}')->render();
            self::assertTrue(false, 'This should not be reached.');
        } catch (\Twig\Error\RuntimeError $e) {
            self::assertInstanceOf(ConflictingMapProvidersOnSamePageException::class, $e->getPrevious());
            self::assertSame('You cannot use the "google_maps" map provider on the same page as the following map providers: "google_maps_2", as their configuration will conflicts with each-other.', $e->getPrevious()->getMessage());
        }
    }

    public function testRenderMap(): void
    {
        $twig = self::getContainer()->get('twig');
        $mapFactory = self::getContainer()->get('ux_map.map_factory');

        $map = $mapFactory->createMap('google_maps_map_1');

        self::assertStringContainsString(
            '<div data-controller="symfony--ux-map--google-maps" data-symfony--ux-map--google-maps-view-value="&#x7B;&quot;mapId&quot;&#x3A;null,&quot;center&quot;&#x3A;null,&quot;zoom&quot;&#x3A;null,&quot;gestureHandling&quot;&#x3A;&quot;auto&quot;,&quot;backgroundColor&quot;&#x3A;null,&quot;disableDoubleClickZoom&quot;&#x3A;false,&quot;zoomControl&quot;&#x3A;true,&quot;zoomControlOptions&quot;&#x3A;&#x7B;&quot;position&quot;&#x3A;22&#x7D;,&quot;mapTypeControl&quot;&#x3A;true,&quot;mapTypeControlOptions&quot;&#x3A;&#x7B;&quot;mapTypeIds&quot;&#x3A;&#x5B;&#x5D;,&quot;position&quot;&#x3A;14,&quot;style&quot;&#x3A;0&#x7D;,&quot;streetViewControl&quot;&#x3A;true,&quot;streetViewControlOptions&quot;&#x3A;&#x7B;&quot;position&quot;&#x3A;22&#x7D;,&quot;fullscreenControl&quot;&#x3A;true,&quot;fullscreenControlOptions&quot;&#x3A;&#x7B;&quot;position&quot;&#x3A;20&#x7D;,&quot;fitBoundsToMarkers&quot;&#x3A;false,&quot;markers&quot;&#x3A;&#x5B;&#x5D;,&quot;infoWindows&quot;&#x3A;&#x5B;&#x5D;&#x7D;"></div>',
            $twig->createTemplate('{{ render_map(map) }}')->render([
                'map' => $map,
            ])
        );

        self::assertStringContainsString(
            '<div data-controller="my-map symfony--ux-map--google-maps" data-symfony--ux-map--google-maps-view-value="&#x7B;&quot;mapId&quot;&#x3A;null,&quot;center&quot;&#x3A;null,&quot;zoom&quot;&#x3A;null,&quot;gestureHandling&quot;&#x3A;&quot;auto&quot;,&quot;backgroundColor&quot;&#x3A;null,&quot;disableDoubleClickZoom&quot;&#x3A;false,&quot;zoomControl&quot;&#x3A;true,&quot;zoomControlOptions&quot;&#x3A;&#x7B;&quot;position&quot;&#x3A;22&#x7D;,&quot;mapTypeControl&quot;&#x3A;true,&quot;mapTypeControlOptions&quot;&#x3A;&#x7B;&quot;mapTypeIds&quot;&#x3A;&#x5B;&#x5D;,&quot;position&quot;&#x3A;14,&quot;style&quot;&#x3A;0&#x7D;,&quot;streetViewControl&quot;&#x3A;true,&quot;streetViewControlOptions&quot;&#x3A;&#x7B;&quot;position&quot;&#x3A;22&#x7D;,&quot;fullscreenControl&quot;&#x3A;true,&quot;fullscreenControlOptions&quot;&#x3A;&#x7B;&quot;position&quot;&#x3A;20&#x7D;,&quot;fitBoundsToMarkers&quot;&#x3A;false,&quot;markers&quot;&#x3A;&#x5B;&#x5D;,&quot;infoWindows&quot;&#x3A;&#x5B;&#x5D;&#x7D;" class="foo"></div>',
            $twig->createTemplate('{{ render_map(map, { "data-controller": "my-map", "class": "foo" }) }}')->render([
                'map' => $map,
            ])
        );
    }
}
