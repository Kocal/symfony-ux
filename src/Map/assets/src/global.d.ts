declare global {
    interface Window {
        __symfony_ux_maps?: {
            providers?: {
                google_maps?: {
                    key: string;
                };
                leaflet?: Record<string, never>;
            };
        };
    }
}
