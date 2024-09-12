import { defineConfig } from 'rolldown';
import glob from 'glob';
import * as path from 'node:path';
import fs from 'fs';

const files = [
    // custom handling for React
    'src/React/assets/src/loader.ts',
    'src/React/assets/src/components.ts',
    // custom handling for Svelte
    'src/Svelte/assets/src/loader.ts',
    'src/Svelte/assets/src/components.ts',
    // custom handling for Vue
    'src/Vue/assets/src/loader.ts',
    'src/Vue/assets/src/components.ts',
    // custom handling for StimulusBundle
    'src/StimulusBundle/assets/src/loader.ts',
    'src/StimulusBundle/assets/src/controllers.ts',
    // custom handling for Bridge
    ...glob.sync('src/*/src/Bridge/*/assets/src/*controller.ts'),
    ...glob.sync('src/*/assets/src/*controller.ts'),
];


/**
 * Guarantees that any files imported from a peer dependency are treated as an external.
 *
 * For example, if we import `chart.js/auto`, that would not normally
 * match the "chart.js" we pass to the "externals" config. This plugin
 * catches that case and adds it as an external.
 *
 * Inspired by https://github.com/oat-sa/rollup-plugin-wildcard-external
 */
const wildcardExternalsPlugin = (peerDependencies) => ({
    name: 'wildcard-externals',
    resolveId(source, importer) {
        if (importer) {
            let matchesExternal = false;
            peerDependencies.forEach((peerDependency) => {
                if (source.includes(`/${peerDependency}/`)) {
                    matchesExternal = true;
                }
            });

            if (matchesExternal) {
                return {
                    id: source,
                    external: true,
                    moduleSideEffects: true
                };
            }
        }

        return null; // other ids should be handled as usually
    }
});

/**
 * Moves the generated TypeScript declaration files to the correct location.
 *
 * This could probably be configured in the TypeScript plugin.
 */
const moveTypescriptDeclarationsPlugin = (packagePath) => ({
    name: 'move-ts-declarations',
    writeBundle: async () => {
        const isBridge = packagePath.includes('src/Bridge');
        const globPattern = isBridge
            ? path.join(packagePath, 'dist', packagePath.replace(/^src\//, ''), '**/*.d.ts')
            : path.join(packagePath, 'dist', '*', 'assets', 'src', '**/*.d.ts')
        const files = glob.sync(globPattern);
        files.forEach((file) => {
            // a bit odd, but remove first 7 or 13 directories, which will leave
            // only the relative path to the file
            const relativePath = file.split('/').slice(isBridge ? 13 : 7).join('/');

            const targetFile = path.join(packagePath, 'dist', relativePath);
            if (!fs.existsSync(path.dirname(targetFile))) {
                fs.mkdirSync(path.dirname(targetFile), { recursive: true });
            }
            fs.renameSync(file, targetFile);
        });
    }
});

export default defineConfig([
    ...files.map(file => {
        const packageRoot = path.join(file, '..', '..');
        const packagePath = path.join(packageRoot, 'package.json');
        const packageData = JSON.parse(fs.readFileSync(packagePath, 'utf8'));
        const peerDependencies = [
            '@hotwired/stimulus',
            ...(packageData.peerDependencies ? Object.keys(packageData.peerDependencies) : []),
        ];

        // custom handling for StimulusBundle
        if (file.includes('StimulusBundle/assets/src/loader.ts')) {
            peerDependencies.push('./controllers.js');
        }
        
        // React, Vue
        if (file.includes('assets/src/loader.ts')) {
            peerDependencies.push('./components.js');
        }

        return {
            input: file,
            output: {
                dir: path.join(packageRoot, 'dist'),
                format: 'esm',
            },
            external: peerDependencies,
            plugins: [
                wildcardExternalsPlugin(peerDependencies),
                moveTypescriptDeclarationsPlugin(packageRoot),
            ]
        };
    }),
]);
