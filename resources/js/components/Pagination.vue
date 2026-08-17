<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface PaginationMeta {
    current_page: number;
    last_page: number;
    total: number;
    links: PaginationLink[];
}

const props = defineProps<{
    meta: PaginationMeta;
    label?: string;
}>();

const limpiarLabel = (label: string) => {
    if (label.includes('Previous')) return '‹';
    if (label.includes('Next')) return '›';
    return label;
};

// 🎯 FILTRO COMPUTADO: Fuerza el truncamiento estricto en el Cliente
const enlacesFiltrados = computed(() => {
    if (!props.meta || !props.meta.links) return [];
    
    const enlaces = props.meta.links;
    const totalenlaces = enlaces.length;
    const actual = props.meta.current_page;
    const ultima = props.meta.last_page;

    // Si hay 7 páginas o menos, dejamos que se rendericen todas con normalidad
    if (ultima <= 7) return enlaces;

    const resultado: (PaginationLink & { esSeparador?: boolean })[] = [];

    enlaces.forEach((link, index) => {
        // Preservamos siempre el botón "Anterior" (primer index) y "Siguiente" (último index)
        if (index === 0 || index === totalenlaces - 1) {
            resultado.push(link);
            return;
        }

        const numPagina = parseInt(link.label);

        if (!isNaN(numPagina)) {
            // Regla estricta: Mostrar la página 1, la última, la actual, y 1 a cada lado
            if (numPagina === 1 || numPagina === ultima || Math.abs(numPagina - actual) <= 1) {
                resultado.push(link);
            } 
            // Si no cumple, inyectamos un separador '...' si no pusimos uno inmediatamente antes
            else {
                const ultimoAgregado = resultado[resultado.length - 1];
                if (ultimoAgregado && !ultimoAgregado.esSeparador && ultimoAgregado.label !== '‹') {
                    resultado.push({
                        url: null,
                        label: '...',
                        active: false,
                        esSeparador: true
                    });
                }
            }
        } else if (link.label.includes('...')) {
            resultado.push(link);
        }
    });

    return resultado;
});
</script>

<template>
    <div v-if="meta && meta.last_page > 1"
        class="flex items-center justify-between border-t border-neutral-200 px-4 py-4 sm:px-6 dark:border-neutral-800 mt-4 bg-white dark:bg-neutral-900 rounded-xl shadow-2xs">
        
        <!-- 📱 Vista Mobile Simplificada -->
        <div class="flex flex-1 justify-between sm:hidden">
            <Link :href="meta.links[0].url || '#'"
                :class="['rounded-md border border-neutral-300 bg-white px-4 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300', !meta.links[0].url && 'pointer-events-none opacity-40']">
                Anterior
            </Link>
            <Link :href="meta.links[meta.links.length - 1].url || '#'"
                :class="['ml-3 rounded-md border border-neutral-300 bg-white px-4 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300', !meta.links[meta.links.length - 1].url && 'pointer-events-none opacity-40']">
                Siguiente
            </Link>
        </div>

        <!-- 🖥️ Vista Desktop Completa Forzada -->
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <p class="text-xs sm:text-sm text-neutral-500 dark:text-neutral-400 font-medium">
                Página <span class="font-bold text-neutral-900 dark:text-neutral-100">{{ meta.current_page }}</span>
                de <span class="font-bold text-neutral-900 dark:text-neutral-100">{{ meta.last_page }}</span>
                <span class="ml-1.5 text-xs text-neutral-400">({{ meta.total }} {{ label || 'registros' }})</span>
            </p>
            
            <nav class="flex flex-wrap items-center gap-1" aria-label="Paginación">
                <!-- 🎯 Iteramos sobre nuestra constante filtrada 'enlacesFiltrados' -->
                <template v-for="(link, key) in enlacesFiltrados" :key="key">
                    
                    <!-- CASO 1: PUNTOS SUSPENSIVOS (...) -->
                    <span 
                        v-if="link.label === '...'"
                        class="inline-flex min-w-[2.25rem] h-9 items-center justify-center text-xs font-bold text-neutral-400 select-none cursor-default"
                    >
                        ...
                    </span>

                    <!-- CASO 2: ENLACES INTERACTIVOS -->
                    <Link 
                        v-else
                        :href="link.url || '#'" 
                        :class="[
                            'inline-flex min-w-[2.25rem] h-9 items-center justify-center rounded-lg px-3 text-xs font-black transition-all duration-150',
                            link.active
                                ? 'bg-[#1A1D56] text-white shadow-xs' 
                                : 'text-neutral-600 hover:bg-neutral-100 dark:text-neutral-400 dark:hover:bg-neutral-800',
                            !link.url && 'pointer-events-none opacity-30'
                        ]"
                    >
                        {{ limpiarLabel(link.label) }}
                    </Link>

                </template>
            </nav>
        </div>
    </div>
</template>