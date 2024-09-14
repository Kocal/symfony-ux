import { Controller } from '@hotwired/stimulus';
import { I } from 'vitest/dist/chunks/reporters.C_zwCd4j';

export type Point = { lat: number; lng: number };

export type MapView<Options, MarkerOptions, InfoWindowOptions, PolygonOptions> = {
    center: Point | null;
    zoom: number | null;
    fitBoundsToMarkers: boolean;
    markers: Array<MarkerDefinition<MarkerOptions, InfoWindowOptions>>;
    polygons: Array<PolygonDefinition<PolygonOptions, InfoWindowOptions>>;
    options: Options;
};

export type MarkerDefinition<MarkerOptions, InfoWindowOptions> = {
    position: Point;
    title: string | null;
    infoWindow?: Omit<InfoWindowDefinition<InfoWindowOptions>, 'position'>;
    /**
     * Raw options passed to the marker constructor, specific to the map provider (e.g.: `L.marker()` for Leaflet).
     */
    rawOptions?: MarkerOptions;
    /**
     * Extra data defined by the developer.
     * They are not directly used by the Stimulus controller, but they can be used by the developer with event listeners:
     *    - `ux:map:marker:before-create`
     *    - `ux:map:marker:after-create`
     */
    extra: Record<string, unknown>;
};

export type PolygonDefinition<PolygonOptions, InfoWindowOptions> = {
    infoWindow?: Omit<InfoWindowDefinition<InfoWindowOptions>, 'position'>;
    points: Array<Point>;
    title: string | null;
    rawOptions?: PolygonOptions;
    extra: Record<string, unknown>;
};

export type InfoWindowDefinition<InfoWindowOptions> = {
    headerContent: string | null;
    content: string | null;
    position: Point;
    opened: boolean;
    autoClose: boolean;
    /**
     * Raw options passed to the info window constructor,
     * specific to the map provider (e.g.: `google.maps.InfoWindow()` for Google Maps).
     */
    rawOptions?: InfoWindowOptions;
    /**
     * Extra data defined by the developer.
     * They are not directly used by the Stimulus controller, but they can be used by the developer with event listeners:
     *    - `ux:map:info-window:before-create`
     *    - `ux:map:info-window:after-create`
     */
    extra: Record<string, unknown>;
};

export default abstract class<
    MapOptions,
    Map,
    MarkerOptions,
    Marker,
    InfoWindowOptions,
    InfoWindow,
    PolygonOptions,
    Polygon,
> extends Controller<HTMLElement> {
    static values = {
        providerOptions: Object,
        view: Object,
    };

    declare viewValue: MapView<MapOptions, MarkerOptions, InfoWindowOptions, PolygonOptions>;

    protected map: Map;
    protected markers: Array<Marker> = [];
    protected infoWindows: Array<InfoWindow> = [];
    protected polygons: Array<Polygon> = [];

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

    protected abstract doCreateMap({
        center,
        zoom,
        options,
    }: {
        center: Point | null;
        zoom: number | null;
        options: MapOptions;
    }): Map;

    public createMarker(definition: MarkerDefinition<MarkerOptions, InfoWindowOptions>): Marker {
        this.dispatchEvent('marker:before-create', { definition });
        const marker = this.doCreateMarker(definition);
        this.dispatchEvent('marker:after-create', { marker });

        this.markers.push(marker);

        return marker;
    }

    createPolygon(definition: PolygonDefinition<PolygonOptions, InfoWindowOptions>): Polygon {
        this.dispatchEvent('polygon:before-create', { definition });
        const polygon = this.doCreatePolygon(definition);
        this.dispatchEvent('polygon:after-create', { polygon });
        this.polygons.push(polygon);
        return polygon;
    }

    protected abstract doCreateMarker(definition: MarkerDefinition<MarkerOptions, InfoWindowOptions>): Marker;
    protected abstract doCreatePolygon(definition: PolygonDefinition<PolygonOptions, InfoWindowOptions>): Polygon;

    protected createInfoWindowMarker({
        definition,
        marker,
    }: {
        definition: MarkerDefinition<MarkerOptions, InfoWindowOptions>['infoWindow'];
        marker: Marker;
    }): InfoWindow {
        this.dispatchEvent('info-window-marker:before-create', { definition, marker });
        const infoWindow = this.doCreateInfoWindowMarker({ definition, marker });
        this.dispatchEvent('info-window-marker:after-create', { infoWindow, marker });

        this.infoWindows.push(infoWindow);

        return infoWindow;
    }

    protected createInfoWindowPolygon({
        definition,
        polygon,
    }: {
        definition: PolygonDefinition<PolygonOptions, InfoWindowOptions>['infoWindow'];
        polygon: Polygon;
    }): InfoWindow {
        this.dispatchEvent('info-window-polygon:before-create', { definition, polygon });
        const infoWindow = this.doCreateInfoWindowPolygon({ definition, polygon });
        this.dispatchEvent('info-window-polygon:after-create', { infoWindow, polygon });

        this.infoWindows.push(infoWindow);

        return infoWindow;
    }

    protected abstract doCreateInfoWindowMarker({
        definition,
        marker,
    }: {
        definition: MarkerDefinition<MarkerOptions, InfoWindowOptions>['infoWindow'];
        marker: Marker;
    }): InfoWindow;

    protected abstract doCreateInfoWindowPolygon({
        definition,
        polygon,
    }: {
        definition: PolygonDefinition<PolygonOptions, InfoWindowOptions>['infoWindow'];
        polygon: Polygon;
    }): InfoWindow;

    protected abstract doFitBoundsToMarkers(): void;

    protected abstract dispatchEvent(name: string, payload: Record<string, unknown>): void;
}
