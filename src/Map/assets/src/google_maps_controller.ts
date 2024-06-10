/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { Controller } from '@hotwired/stimulus';
import type { LoaderOptions } from '@googlemaps/js-api-loader';
import { Loader } from '@googlemaps/js-api-loader';

type MarkerId = number;

export default class extends Controller<HTMLElement> {
    static values = {
        view: Object,
    };

    declare viewValue: {
        mapId: string | null;
        center: null | { lat: number; lng: number };
        zoom: number;
        gestureHandling: string;
        backgroundColor: string;
        disableDoubleClickZoom: boolean;
        zoomControl: boolean;
        zoomControlOptions: google.maps.ZoomControlOptions;
        mapTypeControl: boolean;
        mapTypeControlOptions: google.maps.MapTypeControlOptions;
        streetViewControl: boolean;
        streetViewControlOptions: google.maps.StreetViewControlOptions;
        fullscreenControl: boolean;
        fullscreenControlOptions: google.maps.FullscreenControlOptions;
        markers: Array<{
            _id: MarkerId;
            position: { lat: number; lng: number };
            title: string | null;
        }>;
        infoWindows: Array<{
            headerContent: string | null;
            content: string | null;
            position: { lat: number; lng: number };
            opened: boolean;
            _markerId: MarkerId | null;
            autoClose: boolean;
        }>;
        fitBoundsToMarkers: boolean;
    };

    private loader: Loader;
    private map: google.maps.Map;
    private markers = new Map<number, google.maps.marker.AdvancedMarkerElement>();
    private infoWindows: Array<google.maps.InfoWindow> = [];

    initialize() {
        const providerConfig = window.__symfony_ux_maps.providers?.google_maps;
        if (!providerConfig) {
            throw new Error(
                'Google Maps provider configuration is missing, did you forget to call `{{ ux_map_script_tags() }}`?'
            );
        }

        const loaderOptions: LoaderOptions = {
            apiKey: providerConfig.key,
        };

        this.dispatchEvent('init', {
            loaderOptions,
        });

        this.loader = new Loader(loaderOptions);
    }

    async connect() {
        const { Map: GoogleMap, InfoWindow } = await this.loader.importLibrary('maps');

        const mapOptions: google.maps.MapOptions = {
            gestureHandling: this.viewValue.gestureHandling,
            backgroundColor: this.viewValue.backgroundColor,
            disableDoubleClickZoom: this.viewValue.disableDoubleClickZoom,
            zoomControl: this.viewValue.zoomControl,
            zoomControlOptions: this.viewValue.zoomControlOptions,
            mapTypeControl: this.viewValue.mapTypeControl,
            mapTypeControlOptions: this.viewValue.mapTypeControlOptions,
            streetViewControl: this.viewValue.streetViewControl,
            streetViewControlOptions: this.viewValue.streetViewControlOptions,
            fullscreenControl: this.viewValue.fullscreenControl,
            fullscreenControlOptions: this.viewValue.fullscreenControlOptions,
        };

        if (this.viewValue.mapId) {
            mapOptions.mapId = this.viewValue.mapId;
        }

        if (this.viewValue.center) {
            mapOptions.center = this.viewValue.center;
        }

        if (this.viewValue.zoom) {
            mapOptions.zoom = this.viewValue.zoom;
        }

        this.dispatchEvent('pre-connect', {
            mapOptions,
        });

        this.map = new GoogleMap(this.element, mapOptions);

        if (this.viewValue.markers) {
            const { AdvancedMarkerElement } = await this.loader.importLibrary('marker');

            this.viewValue.markers.forEach((markerConfiguration) => {
                const marker = new AdvancedMarkerElement({
                    position: markerConfiguration.position,
                    title: markerConfiguration.title,
                    map: this.map,
                });

                this.markers.set(markerConfiguration._id, marker);
            });

            if (this.viewValue.fitBoundsToMarkers) {
                const bounds = new google.maps.LatLngBounds();
                this.markers.forEach((marker) => {
                    if (!marker.position) {
                        return;
                    }

                    bounds.extend(marker.position);
                });
                this.map.fitBounds(bounds);
            }
        }

        this.viewValue.infoWindows.forEach((infoWindowConfiguration) => {
            const marker = infoWindowConfiguration._markerId
                ? this.markers.get(infoWindowConfiguration._markerId)
                : undefined;

            const infoWindow = new InfoWindow({
                headerContent: this.createTextOrElement(infoWindowConfiguration.headerContent),
                content: this.createTextOrElement(infoWindowConfiguration.content),
                position: infoWindowConfiguration.position,
            });

            this.infoWindows.push(infoWindow);

            if (infoWindowConfiguration.opened) {
                infoWindow.open({
                    map: this.map,
                    shouldFocus: false,
                    anchor: marker,
                });
            }

            if (marker) {
                marker.addListener('click', () => {
                    if (infoWindowConfiguration.autoClose) {
                        this.closeInfoWindowsExcept(infoWindow);
                    }

                    infoWindow.open({
                        map: this.map,
                        anchor: marker,
                    });
                });
            }
        });

        this.dispatchEvent('connect', {
            map: this.map,
            markers: this.markers,
            infoWindows: this.infoWindows,
        });
    }

    private createTextOrElement(content: string | null): string | HTMLElement | null {
        if (!content) {
            return null;
        }

        if (content.includes('<') /* we assume it's HTML if it includes "<" */) {
            const div = document.createElement('div');
            div.innerHTML = content;
            return div;
        }

        return content;
    }

    private closeInfoWindowsExcept(infoWindow: google.maps.InfoWindow) {
        this.infoWindows.forEach((otherInfoWindow) => {
            if (otherInfoWindow !== infoWindow) {
                otherInfoWindow.close();
            }
        });
    }

    private dispatchEvent(name: string, payload: any) {
        this.dispatch(name, { detail: payload, prefix: 'google-maps' });
    }
}
