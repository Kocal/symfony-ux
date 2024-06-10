/// <reference types="google.maps" />
import { Controller } from '@hotwired/stimulus';
type MarkerId = number;
export default class extends Controller<HTMLElement> {
    static values: {
        view: ObjectConstructor;
    };
    viewValue: {
        mapId: string | null;
        center: null | {
            lat: number;
            lng: number;
        };
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
            position: {
                lat: number;
                lng: number;
            };
            title: string | null;
        }>;
        infoWindows: Array<{
            headerContent: string | null;
            content: string | null;
            position: {
                lat: number;
                lng: number;
            };
            opened: boolean;
            _markerId: MarkerId | null;
            autoClose: boolean;
        }>;
        fitBoundsToMarkers: boolean;
    };
    private loader;
    private map;
    private markers;
    private infoWindows;
    initialize(): void;
    connect(): Promise<void>;
    private createTextOrElement;
    private closeInfoWindowsExcept;
    private dispatchEvent;
}
export {};
