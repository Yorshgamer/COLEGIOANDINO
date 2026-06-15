import type { Auth } from '@/types/auth';
import type { Page } from '@inertiajs/core';
import type { Router, createHeadManager } from '@inertiajs/core';
// 1. Importamos los tipados nativos de Ziggy/Wayfinder para que reconozca los parámetros
import type { Config, RouteParam, RouteParamsWithQueryOverload } from 'ziggy-js';

// Extend ImportMeta interface for Vite...
declare module 'vite/client' {
    interface ImportMetaEnv {
        readonly VITE_APP_NAME: string;
        [key: string]: string | boolean | undefined;
    }

    interface ImportMeta {
        readonly env: ImportMetaEnv;
        readonly glob: <T>(pattern: string) => Record<string, () => Promise<T>>;
    }
}

declare module '@inertiajs/core' {
    export interface InertiaConfig {
        sharedPageProps: {
            name: string;
            auth: Auth;
            sidebarOpen: boolean;
            flash: {
                success?: string;
                error?: string;
                error_masivo?: string[]; // <-- Le avisamos que es un array opcional de textos
            };
            [key: string]: unknown;
        };
    }
}

declare module 'vue' {
    interface ComponentCustomProperties {
        $inertia: typeof Router;
        $page: Page;
        $headManager: ReturnType<typeof createHeadManager>;
        // 2. Le avisamos a los componentes de Vue que la función $route o route es válida en el template
        route: typeof route;
    }
}

// 3. REGISTRO GLOBAL DE LA FUNCIÓN DE RUTAS DE LARAVEL PARA TYPESCRIPT
declare global {
    function route(): string;
    function route(
        name: string,
        params?: RouteParamsWithQueryOverload | RouteParam,
        absolute?: boolean,
        config?: Config
    ): string;
}

export {};