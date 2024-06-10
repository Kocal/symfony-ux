import { Controller } from '@hotwired/stimulus';
import 'leaflet/dist/leaflet.min.css';
import type { MarkerOptions } from 'leaflet';
type MarkerId = number;
export default class extends Controller<HTMLElement> {
    static values: {
        view: ObjectConstructor;
    };
    viewValue: {
        center: null | {
            lat: number;
            lng: number;
        };
        zoom: number | null;
        tileLayer: {
            url: string;
            attribution: string;
        } & Record<string, unknown>;
        fitBoundsToMarkers: boolean;
        markers: Array<{
            _id: MarkerId;
            position: {
                lat: number;
                lng: number;
            };
        } & MarkerOptions>;
        popups: Array<{
            _markerId: MarkerId | null;
            content: string;
            position: {
                lat: number;
                lng: number;
            };
            opened: boolean;
            autoClose: boolean;
        }>;
    };
    private map;
    private markers;
    private popups;
    connect(): void;
    private setupTileLayer;
    private dispatchEvent;
}
export {};
