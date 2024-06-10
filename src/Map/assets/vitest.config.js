import { defineConfig, mergeConfig } from 'vitest/config';
import configShared from '../../../vitest.config.js'

export default mergeConfig(
    configShared,
    defineConfig({
        resolve: {
            alias: {
                'leaflet/dist/leaflet.min.css': require.resolve('leaflet/dist/leaflet.css'),
            },
        },
        test: {
            // We need a browser(-like) environment to run the tests
            environment: 'happy-dom',
        },
    })
);
