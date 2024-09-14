import { Controller } from '@hotwired/stimulus';

class default_1 extends Controller {
    constructor() {
        super(...arguments);
        this.markers = [];
        this.infoWindows = [];
        this.polygons = [];
    }
    connect() {
        const { center, zoom, options, markers, polygons, fitBoundsToMarkers } = this.viewValue;
        this.dispatchEvent('pre-connect', { options });
        this.map = this.doCreateMap({ center, zoom, options });
        markers.forEach((marker) => this.createMarker(marker));
        polygons.forEach((polygon) => this.createPolygon(polygon));
        if (fitBoundsToMarkers) {
            this.doFitBoundsToMarkers();
        }
        this.dispatchEvent('connect', {
            map: this.map,
            markers: this.markers,
            polygons: this.polygons,
            infoWindows: this.infoWindows,
        });
    }
    createMarker(definition) {
        this.dispatchEvent('marker:before-create', { definition });
        const marker = this.doCreateMarker(definition);
        this.dispatchEvent('marker:after-create', { marker });
        this.markers.push(marker);
        return marker;
    }
    createPolygon(definition) {
        this.dispatchEvent('polygon:before-create', { definition });
        const polygon = this.doCreatePolygon(definition);
        this.dispatchEvent('polygon:after-create', { polygon });
        this.polygons.push(polygon);
        return polygon;
    }
    createInfoWindowMarker({ definition, marker, }) {
        this.dispatchEvent('info-window-marker:before-create', { definition, marker });
        const infoWindow = this.doCreateInfoWindowMarker({ definition, marker });
        this.dispatchEvent('info-window-marker:after-create', { infoWindow, marker });
        this.infoWindows.push(infoWindow);
        return infoWindow;
    }
    createInfoWindowPolygon({ definition, polygon, }) {
        this.dispatchEvent('info-window-polygon:before-create', { definition, polygon });
        const infoWindow = this.doCreateInfoWindowPolygon({ definition, polygon });
        this.dispatchEvent('info-window-polygon:after-create', { infoWindow, polygon });
        this.infoWindows.push(infoWindow);
        return infoWindow;
    }
}
default_1.values = {
    providerOptions: Object,
    view: Object,
};

export { default_1 as default };
