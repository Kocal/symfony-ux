/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

'use strict';

import { Controller } from '@hotwired/stimulus';
import 'leaflet/dist/leaflet.min.css';
import type { Map as LeafletMap, MapOptions, Marker, MarkerOptions, Popup } from 'leaflet';
import L from 'leaflet';

type MarkerId = number;

export default class extends Controller<HTMLElement> {
    static values = {
        view: Object,
    };

    declare viewValue: {
        center: null | { lat: number; lng: number };
        zoom: number | null;
        tileLayer: { url: string; attribution: string } & Record<string, unknown>;
        fitBoundsToMarkers: boolean;
        markers: Array<
            {
                _id: MarkerId;
                position: { lat: number; lng: number };
            } & MarkerOptions
        >;
        popups: Array<{
            _markerId: MarkerId | null;
            content: string;
            position: { lat: number; lng: number };
            opened: boolean;
            autoClose: boolean;
        }>;
    };

    private map: LeafletMap;
    private markers = new Map<MarkerId, Marker>();
    private popups: Array<Popup> = [];

    connect() {
        const mapOptions: MapOptions = {
            center: this.viewValue.center || undefined,
            zoom: this.viewValue.zoom || undefined,
        };

        this.dispatchEvent('pre-connect', {
            mapOptions,
        });

        this.map = L.map(this.element, mapOptions);

        this.setupTileLayer();

        this.viewValue.markers.forEach((markerConfiguration) => {
            const { _id, position, ...options } = markerConfiguration;
            const marker = L.marker(position, options).addTo(this.map);

            this.markers.set(_id, marker);
        });

        this.viewValue.popups.forEach((popupConfiguration) => {
            let popup: Popup;
            if (popupConfiguration._markerId) {
                const marker = this.markers.get(popupConfiguration._markerId);
                if (!marker) {
                    return;
                }
                marker.bindPopup(popupConfiguration.content, {
                    autoClose: popupConfiguration.autoClose,
                });
                popup = marker.getPopup()!;
            } else {
                popup = L.popup({
                    content: popupConfiguration.content,
                    autoClose: popupConfiguration.autoClose,
                });

                popup.setLatLng(popupConfiguration.position);
            }

            if (popupConfiguration.opened) {
                popup.openOn(this.map);
            }

            this.popups.push(popup);
        });

        if (this.viewValue.fitBoundsToMarkers) {
            this.map.fitBounds(
                Array.from(this.markers.values()).map((marker) => {
                    const position = marker.getLatLng();
                    return [position.lat, position.lng];
                })
            );
        }

        this.dispatchEvent('connect', {
            map: this.map,
            markers: this.markers,
            popups: this.popups,
        });
    }

    private setupTileLayer() {
        const { url, attribution, ...options } = this.viewValue.tileLayer;

        L.tileLayer(url, {
            attribution,
            ...options,
        }).addTo(this.map);
    }

    private dispatchEvent(name: string, payload: any) {
        this.dispatch(name, { detail: payload, prefix: 'leaflet' });
    }
}
