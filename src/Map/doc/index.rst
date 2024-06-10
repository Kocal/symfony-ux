Symfony UX Map
==============

**EXPERIMENTAL** This component is currently experimental and is likely
to change, or even change drastically.

Symfony UX Map is a Symfony bundle integrating interactive Maps in
Symfony applications. It is part of `the Symfony UX initiative`_.

Installation
------------

.. caution::

    Before you start, make sure you have `StimulusBundle configured in your app`_.

Install the bundle using Composer and Symfony Flex:

.. code-block:: terminal

    $ composer require symfony/ux-map

If you're using WebpackEncore, install your assets and restart Encore (not
needed if you're using AssetMapper):

.. code-block:: terminal

    $ npm install --force
    $ npm run watch

    # or use yarn
    $ yarn install --force
    $ yarn watch

After installing the bundle, ensure the line ``{{ ux_map_script_tags() }}`` is present in your Twig template, e.g.:

.. code-block:: twig

        {% block javascripts %}
            {% block importmap %}{{ importmap('app') }}{% endblock %}
            {{ ux_map_script_tags() }}
        {% endblock %}
        
Usage
-----

Configuration
~~~~~~~~~~~~~

Configuration is done in your ``config/packages/ux_map.yaml`` file, where you can define the providers and maps you want to use.

Providers are the services that will be used to render the maps. 
They can be configured with options that are specific to the provider, like the API key for Google Maps:

.. code-block:: yaml
    
    ux_map:
        providers: 
            google_maps:
                provider: google_maps
                options:
                     key: '%env(GOOGLE_MAPS_API_KEY)%'
            
            leaflet:
                provider: leaflet         

Maps are the actual maps that will be rendered. 
They are configured with the provider they will use, and can have options that are specific to the map, 
like the center and zoom level:

.. code-block:: yaml
    
    ux_map:
        maps:
            google_maps_map_1: 
                provider: google_maps
                options:
                    center: [48.8566, 2.3522]
                    zoom: 12
             
            leaflet_map:
                provider: leaflet

.. note::

    Even if it is possible to render several maps with different providers, 
    it will not be possible to render two maps with two providers of the same type 
    but with a different configuration, since they will conflict.


Google Maps
~~~~~~~~~~~

To use Google Maps on your application, you need to enable the Google Maps controller in your ``assets/controllers.json``:

.. code-block:: json
    
    {
        "controllers": {
            "@symfony/ux-map": {
                "google-maps": {
                    "enabled": true,
                    "fetch": "lazy"
                },
                "leaflet": {
                    "enabled": false,
                    "fetch": "lazy"
                }
            },
        },
        "entrypoints": []
    }


Then, you need to configure a new provider and a new map, in your ``config/packages/ux_map.yaml``:

.. code-block:: yaml

    ux_map:
        providers: 
            google_maps:
                provider: google_maps
                options:
                     key: '%env(GOOGLE_MAPS_API_KEY)%'

        maps:
            # With the default options
            google_maps_map_1: 
                provider: google_maps
                    
            # With all supported options
            google_maps_map_2:
                provider: google_maps
                options:
                    mapId: 'DEMO_MAP_ID'
                    center: [48.8566, 2.3522]
                    zoom: 12
                    gestureHandling: auto
                    backgroundColor: '#f8f9fa'
                    enableDoubleClickZoom: true
                    zoomControl: true
                    mapTypeControl: true
                    streetViewControl: true
                    fullscreenControl: true
                    fitBoundsToMarkers: true

Then, you must create the Map instance in your PHP code (e.g. in a controller)::
    
    namespace App\Controller;
    
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;
    use Symfony\UX\Map\Factory\MapFactoryInterface;
    use Symfony\UX\Map\LatLng;
    use Symfony\UX\Map\Provider\GoogleMaps;
    
    final class ContactController extends AbstractController
    {
        #[Route('/contact')]
        public function __invoke(MapFactoryInterface $mapFactory): Response
        {
            // 1. The map is created with the factory, you must pass the map name you defined in the configuration (here 'google_maps_map_1'),
            // you can also pass the map options as a second argument.
        
            /** @var GoogleMaps\Map $map */
            $map = $mapFactory->createMap('google_maps_map_1');
            
            // 2. The map can be programmatically configured with a fluent API, you can change the center, zoom, configure controls, etc...
            $map
                ->setMapId("2b2d73ba4b8c7b41")
                ->setCenter(new LatLng(46.903354, 1.888334))
                ->setZoom(6)
                ->enableFitBoundsToMarkers()
                ->enableStreetViewControl(false)
                ->enableMapTypeControl(false)
                ->setFullscreenControlOptions(new GoogleMaps\FullscreenControlOptions(
                    position: GoogleMaps\ControlPosition::BLOCK_START_INLINE_START,
                ))
                ->setZoomControlOptions(new GoogleMaps\ZoomControlOptions(
                    position: GoogleMaps\ControlPosition::BLOCK_START_INLINE_END,
                ));
            
            // 3. You can add also add markers
            $map
                ->addMarker($paris = new GoogleMaps\Marker(position: new LatLng(48.8566, 2.3522), title: 'Paris'))
                ->addMarker($lyon = new GoogleMaps\Marker(position: new LatLng(45.7640, 4.8357), title: 'Lyon'))
                ->addMarker(new GoogleMaps\Marker(position: new LatLng(43.2965, 5.3698), title: 'Marseille'));
                
            // 4. You can also add info windows to the markers or to a position
            $map
                ->addInfoWindow(new GoogleMaps\InfoWindow(
                    headerContent: '<b>Paris</b>',
                    content: "Capital of France, is a major European city and a world center for art, fashion, gastronomy and culture.",
                    marker: $paris, // Attach the info window to the marker, when the marker is clicked, the info window will open
                    opened: true, // Open the info window by default
                ))
                ->addInfoWindow(new GoogleMaps\InfoWindow(
                    headerContent: '<b>Lyon</b>',
                    content: 'The French town in the historic Rhône-Alpes region, located at the junction of the Rhône and Saône rivers.',
                    marker: $lyon
                ))
                ->addInfoWindow(new GoogleMaps\InfoWindow(
                    headerContent: '<b>Strasbourg</b>',
                    content: "The French town of Alsace is home to the European Parliament and the Council of Europe.",
                    position: new LatLng(48.5846, 7.7507), // Attach the info window to a position, not to a marker
                ));
            ;
            
            // 4. Finally, you must inject the map in your template to render it
            return $this->render('contact/index.html.twig', [
                'map' => $map,
            ]);
        }
    }

Finally, you can render the map in your Twig template:

.. code-block:: twig

    {{ render_map(map) }}
    
    {# or with custom attributes #}
    {{ render_map(map, { 'data-controller': 'my-map', style: 'height: 300px' }) }}

If everything went well, you should see a map with markers and info windows in your page.

Leaflet
~~~~~~~

To use Google Maps on your application, you need to enable the Leaflet controller in your ``assets/controllers.json``:

.. code-block:: json
    
    {
        "controllers": {
            "@symfony/ux-map": {
                "google-maps": {
                    "enabled": false,
                    "fetch": "lazy"
                },
                "leaflet": {
                    "enabled": true,
                    "fetch": "lazy"
                }
            },
        },
        "entrypoints": []
    }


Then, you need to configure a new provider and a new map, in your ``config/packages/ux_map.yaml``:

.. code-block:: yaml

    ux_map:
        providers: 
            leaflet:
                provider: leaflet

        maps:
            # With the default options
            leaflet_map_1: 
                provider: google_maps
                    
            # With all supported options
            leaflet_map_2:
                provider: google_maps
                options:
                    center: [48.8566, 2.3522]
                    zoom: 12
                    tileLayer:
                        url: 'https://tile.openstreetmap.org/{z}/{x}/{y}.png'
                        attribution: '© OpenStreetMap contributors'
                        options:
                            maxZoom: 19
                    fitBoundsToMarkers: true

Then, you must create the Map instance in your PHP code (e.g. in a controller)::
    
    namespace App\Controller;
    
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;
    use Symfony\UX\Map\Factory\MapFactoryInterface;
    use Symfony\UX\Map\LatLng;
    use Symfony\UX\Map\Provider\Leaflet;
    
    final class ContactController extends AbstractController
    {
        #[Route('/contact')]
        public function __invoke(MapFactoryInterface $mapFactory): Response
        {
            // 1. The map is created with the factory, you must pass the map name you defined in the configuration (here 'leaflet_map_1'),
            // you can also pass the map options as a second argument.
        
            /** @var Leaflet\Map $map */
            $map = $mapFactory->createMap('leaflet_map_1');
            
            // 2. The map can be programmatically configured with a fluent API, you can change the center, zoom, configure controls, etc...
            $map
                ->setCenter(new LatLng(46.903354, 1.888334))
                ->setZoom(6)
                ->enableFitBoundsToMarkers();
            
            // 3. You can add also add markers
            $map
                ->addMarker($paris = new Leaflet\Marker(position: new LatLng(48.8566, 2.3522), title: 'Paris'))
                ->addMarker($lyon = new Leaflet\Marker(position: new LatLng(45.7640, 4.8357), title: 'Lyon'))
                ->addMarker(new Leaflet\Marker(position: new LatLng(43.2965, 5.3698), title: 'Marseille'));
                
            // 4. You can also add popups to the markers or to a position
            $map
                ->addInfoWindow(new Leaflet\Popup(
                    content: "<b>Paris</b>, capital of France, is a major European city and a world center for art, fashion, gastronomy and culture.",
                    marker: $paris, // Attach the info window to the marker, when the marker is clicked, the info window will open
                    opened: true, // Open the info window by default
                ))
                ->addInfoWindow(new Leaflet\Popup(
                    content: '<b>Lyon</b>, French town in the historic Rhône-Alpes region, located at the junction of the Rhône and Saône rivers.',
                    marker: $lyon
                ))
                ->addInfoWindow(new Leaflet\Popup(
                    content: "<b>Strasbourg</b>, French town of Alsace is home to the European Parliament and the Council of Europe.",
                    position: new LatLng(48.5846, 7.7507), // Attach the info window to a position, not to a marker
                ));
            ;
            
            // 4. Finally, you must inject the map in your template to render it
            return $this->render('contact/index.html.twig', [
                'map' => $map,
            ]);
        }
    }

Finally, you can render the map in your Twig template:

.. code-block:: twig

    {{ render_map(map) }}
    
    {# or with custom attributes #}
    {{ render_map(map, { 'data-controller': 'my-map', style: 'height: 300px' }) }}

If everything went well, you should see a map with markers and popups in your page.

.. _using-with-asset-mapper:

Using with AssetMapper
----------------------

Using this library with AssetMapper is possible.

When installing with AssetMapper, Flex will add a few new items to your ``importmap.php``::

    '@symfony/ux-map/google-maps' => [
        'path' => '@symfony/ux-map/google_maps_controller.js',
    ],
    '@symfony/ux-map/leaflet' => [
        'path' => '@symfony/ux-map/leaflet_controller.js',
    ],
    '@googlemaps/js-api-loader' => [
        'version' => '1.16.6',
    ],
    'leaflet' => [
        'version' => '1.9.4',
    ],
    'leaflet/dist/leaflet.min.css' => [
        'version' => '1.9.4',
        'type' => 'css',
    ],

Backward Compatibility promise
------------------------------

This bundle aims at following the same Backward Compatibility promise as
the Symfony framework:
https://symfony.com/doc/current/contributing/code/bc.html

.. _`the Symfony UX initiative`: https://symfony.com/ux
.. _StimulusBundle configured in your app: https://symfony.com/bundles/StimulusBundle/current/index.html
