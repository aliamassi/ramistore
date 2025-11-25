
import {fileURLToPath} from 'node:url'
import svgLoader from 'vite-svg-loader'
import vuetify, { transformAssetUrls } from 'vite-plugin-vuetify'
// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
    modules: ['@vueuse/nuxt', '@nuxtjs/device', '@pinia/nuxt', '@qirolab/nuxt-sanctum-authentication'],
    css: [
        'vuetify/styles',
        '@mdi/font/css/materialdesignicons.css',
        '@styles/styles.scss',
        '@/plugins/iconify/icons.css',
        '@layouts/styles/index.scss',
        '@core/scss/template/index.scss',
        '@/assets/styles/styles.scss',
    ],


    runtimeConfig: {
        public: {
            apiBase: 'https://ramistore.ktel-uae.com',
            // apiBase: 'http://localhost:8000',
            laravelSanctum: {
                apiUrl: 'https://ramistore.ktel-uae.com',
                // apiUrl: 'http://localhost:8000',
                authMode: 'cookie',
                sanctumEndpoints: {
                    csrf: '/sanctum/csrf-cookie',
                    login: '/panel/login',
                    user: '/panel/user',
                    logout: '/panel/logout',
                },
                redirect: {
                    loginPath: '/login',              // Nuxt page
                    redirectToAfterLogin: '/products',// Nuxt page
                    redirectToAfterLogout: '/login',  // Nuxt page
                },
            },
        }
    },

    app: {
        baseURL: '/frontend/',
        // baseURL: '/',
        head: {
            titleTemplate: 'Rami Store Panel',
            title: 'Store',

            link: [{
                rel: 'icon',
                type: 'image/x-icon',
                href: '/favicon.ico',

            }],
        },
    },

    devtools: {
        enabled: true,
    },


    components: {
        dirs: [{
            path: '@/@core/components',
            pathPrefix: false,
        }, {
            path: '~/components/global',
            global: true,
        }, {
            path: '~/components',
            pathPrefix: false,
        }],
    },

    plugins: ['@/plugins/vuetify/index.ts', '@/plugins/iconify/index.ts'],

    imports: {
        dirs: ['./@core/utils', './@core/composable/', './plugins/*/composables/*'],
    },

    hooks: {},

    experimental: {
        typedPages: true,
    },

    typescript: {
        tsConfig: {
            compilerOptions: {
                paths: {
                    '@/*': ['../*'],
                    '@layouts/*': ['../@layouts/*'],
                    '@layouts': ['../@layouts'],
                    '@core/*': ['../@core/*'],
                    '@core': ['../@core'],
                    '@images/*': ['../assets/images/*'],
                    '@styles/*': ['../assets/styles/*'],
                },
            },
        },
    },

    // ℹ️ Disable source maps until this is resolved: https://github.com/vuetifyjs/vuetify-loader/issues/290
    sourcemap: {
        server: false,
        client: false,
    },

    vue: {
        compilerOptions: {
            isCustomElement: tag => tag === 'swiper-container' || tag === 'swiper-slide',
        },
    },

    vite: {
        define: {'process.env': {}},

        resolve: {
            alias: {
                '@': fileURLToPath(new URL('.', import.meta.url)),
                '@core': fileURLToPath(new URL('./@core', import.meta.url)),
                '@layouts': fileURLToPath(new URL('./@layouts', import.meta.url)),
                '@images': fileURLToPath(new URL('./assets/images/', import.meta.url)),
                '@styles': fileURLToPath(new URL('./assets/styles/', import.meta.url)),
                '@configured-variables': fileURLToPath(new URL('./assets/styles/variables/_template.scss', import.meta.url)),
            },
        },

        build: {
            chunkSizeWarningLimit: 5000,
        },

        optimizeDeps: {
            exclude: ['vuetify'],
            entries: [
                './**/*.vue',
            ],
        },

        plugins: [
            svgLoader(),
            vuetify({
                autoImport: true,
                styles: {
                    configFile: 'assets/styles/variables/_vuetify.scss',
                },
            }),
        ],
    },

    build: {
        transpile: ['vuetify'],
    },

    compatibilityDate: '2025-10-01',

})
