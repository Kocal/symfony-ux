import { Controller } from '@hotwired/stimulus';
import { Loader } from '@googlemaps/js-api-loader';

class default_1 extends Controller {
    constructor() {
        super(...arguments);
        this.markers = new Map();
        this.infoWindows = [];
    }
    initialize() {
        var _a;
        const providerConfig = (_a = window.__symfony_ux_maps.providers) === null || _a === void 0 ? void 0 : _a.google_maps;
        if (!providerConfig) {
            throw new Error('Google Maps provider configuration is missing, did you forget to call `{{ ux_map_script_tags() }}`?');
        }
        const loaderOptions = {
            apiKey: providerConfig.key,
        };
        this.dispatchEvent('init', {
            loaderOptions,
        });
        this.loader = new Loader(loaderOptions);
    }
    async connect() {
        const { Map: GoogleMap, InfoWindow } = await this.loader.importLibrary('maps');
        const mapOptions = {
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
    createTextOrElement(content) {
        if (!content) {
            return null;
        }
        if (content.includes('<')) {
            const div = document.createElement('div');
            div.innerHTML = content;
            return div;
        }
        return content;
    }
    closeInfoWindowsExcept(infoWindow) {
        this.infoWindows.forEach((otherInfoWindow) => {
            if (otherInfoWindow !== infoWindow) {
                otherInfoWindow.close();
            }
        });
    }
    dispatchEvent(name, payload) {
        this.dispatch(name, { detail: payload, prefix: 'google-maps' });
    }
}
default_1.values = {
    view: Object,
};

export { default_1 as default };
