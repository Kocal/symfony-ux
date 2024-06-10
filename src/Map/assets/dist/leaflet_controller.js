import { Controller } from '@hotwired/stimulus';
import 'leaflet/dist/leaflet.min.css';
import L from 'leaflet';

/******************************************************************************
Copyright (c) Microsoft Corporation.

Permission to use, copy, modify, and/or distribute this software for any
purpose with or without fee is hereby granted.

THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES WITH
REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF MERCHANTABILITY
AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY SPECIAL, DIRECT,
INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES WHATSOEVER RESULTING FROM
LOSS OF USE, DATA OR PROFITS, WHETHER IN AN ACTION OF CONTRACT, NEGLIGENCE OR
OTHER TORTIOUS ACTION, ARISING OUT OF OR IN CONNECTION WITH THE USE OR
PERFORMANCE OF THIS SOFTWARE.
***************************************************************************** */

function __rest(s, e) {
    var t = {};
    for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p) && e.indexOf(p) < 0)
        t[p] = s[p];
    if (s != null && typeof Object.getOwnPropertySymbols === "function")
        for (var i = 0, p = Object.getOwnPropertySymbols(s); i < p.length; i++) {
            if (e.indexOf(p[i]) < 0 && Object.prototype.propertyIsEnumerable.call(s, p[i]))
                t[p[i]] = s[p[i]];
        }
    return t;
}

class default_1 extends Controller {
    constructor() {
        super(...arguments);
        this.markers = new Map();
        this.popups = [];
    }
    connect() {
        const mapOptions = {
            center: this.viewValue.center || undefined,
            zoom: this.viewValue.zoom || undefined,
        };
        this.dispatchEvent('pre-connect', {
            mapOptions,
        });
        this.map = L.map(this.element, mapOptions);
        this.setupTileLayer();
        this.viewValue.markers.forEach((markerConfiguration) => {
            const { _id, position } = markerConfiguration, options = __rest(markerConfiguration, ["_id", "position"]);
            const marker = L.marker(position, options).addTo(this.map);
            this.markers.set(_id, marker);
        });
        this.viewValue.popups.forEach((popupConfiguration) => {
            let popup;
            if (popupConfiguration._markerId) {
                const marker = this.markers.get(popupConfiguration._markerId);
                if (!marker) {
                    return;
                }
                marker.bindPopup(popupConfiguration.content, {
                    autoClose: popupConfiguration.autoClose,
                });
                popup = marker.getPopup();
            }
            else {
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
            this.map.fitBounds(Array.from(this.markers.values()).map((marker) => {
                const position = marker.getLatLng();
                return [position.lat, position.lng];
            }));
        }
        this.dispatchEvent('connect', {
            map: this.map,
            markers: this.markers,
            popups: this.popups,
        });
    }
    setupTileLayer() {
        const _a = this.viewValue.tileLayer, { url, attribution } = _a, options = __rest(_a, ["url", "attribution"]);
        L.tileLayer(url, Object.assign({ attribution }, options)).addTo(this.map);
    }
    dispatchEvent(name, payload) {
        this.dispatch(name, { detail: payload, prefix: 'leaflet' });
    }
}
default_1.values = {
    view: Object,
};

export { default_1 as default };
