<script setup lang="ts">
import WebLayout from '@/layouts/web/WebLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import { FileText, Download, ExternalLink, Calendar, ChevronLeft, ChevronRight, MessageCircle } from '@lucide/vue';

interface BoletinItem {
    id: number;
    numero: string;
    titulo: string;
    descripcion: string | null;
    fecha_publicacion: string;
    archivo_pdf: string;
}

interface PaginatedData<T> {
    data: T[];
    links: {
        url: string | null;
        label: string;
        active: boolean;
    }[];
    current_page: number;
    last_page: number;
    prev_page_url: string | null;
    next_page_url: string | null;
}

const props = defineProps<{
    title?: string;
    boletines: PaginatedData<BoletinItem>;
}>();

const sectionRef = ref<HTMLElement | null>(null);

const formatDate = (dateStr: string) => {
    if (!dateStr) return '';
    // Limpia la fecha para obtener solo YYYY-MM-DD
    const cleanDate = dateStr.includes('T') ? dateStr.split('T')[0] : dateStr;
    const parts = cleanDate.split('-');

    if (parts.length === 3) {
        const year = parseInt(parts[0], 10);
        const month = parseInt(parts[1], 10) - 1;
        const day = parseInt(parts[2], 10);

        const date = new Date(year, month, day);
        return date.toLocaleDateString('es-PE', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
    }
    return dateStr;
};

onMounted(() => {
    const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (prefersReduced || !sectionRef.value) return;

    const targets = sectionRef.value.querySelectorAll('[data-reveal]');
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry, i) => {
                if (entry.isIntersecting) {
                    setTimeout(() => entry.target.classList.add('is-visible'), i * 90);
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.15 }
    );

    targets.forEach((el) => observer.observe(el));
});
</script>

<template>

    <Head :title="title || 'Boletines Semanales - Colegio Andino'" />

    <WebLayout>
        <!-- Header con Breadcrumb -->
        <section
            class="relative bg-gradient-to-b from-neutral-100 to-white py-14 md:py-20 border-b border-neutral-200/60 overflow-hidden font-sans">
            <div
                class="absolute top-0 left-1/2 -translate-x-1/2 w-24 h-1 bg-gradient-to-r from-[#1a1d56] to-[#ae0c26] rounded-full">
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <span class="text-xs font-bold uppercase tracking-[0.25em] text-[#ae0c26]/80 block mb-3">
                    Publicaciones Oficiales
                </span>
                <h1 class="font-display text-4xl md:text-6xl font-black text-[#1a1d56] tracking-tight mb-4 uppercase">
                    Boletines
                </h1>
                <nav aria-label="Breadcrumb" class="flex justify-center items-center gap-2 text-sm font-semibold">
                    <Link href="/" class="text-[#ae0c26] hover:underline transition-all">Inicio</Link>
                    <span class="text-neutral-400">/</span>
                    <span class="text-neutral-500">Boletines</span>
                </nav>
            </div>
        </section>

        <!-- Contenido Principal -->
        <section ref="sectionRef" class="py-16 md:py-24 bg-white font-sans">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">

                    <!-- Columna Izquierda: Grid de Boletines -->
                    <div class="lg:col-span-8 space-y-8" data-reveal>
                        <div class="border-b border-neutral-100 pb-4">
                            <span class="text-xs font-black uppercase tracking-wider text-[#ae0c26] block">Archivo
                                Digital</span>
                            <h2 class="font-display text-3xl font-black text-[#1a1d56]">Nuestros Boletines Semanales
                            </h2>
                        </div>

                        <!-- Grid de Cards de Boletines -->
                        <div v-if="boletines.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div v-for="boletin in boletines.data" :key="boletin.id"
                                class="bg-white rounded-3xl border border-neutral-200/80 overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between group">

                                <div>
                                    <!-- Contenedor Visual con Previsualización Embebida -->
                                    <div
                                        class="h-56 relative bg-neutral-900 overflow-hidden border-b border-neutral-100">
                                        <!-- Visor / Thumbnail del PDF -->
                                        <object
                                            :data="'/' + boletin.archivo_pdf + '#toolbar=0&navpanes=0&scrollbar=0&view=FitH'"
                                            type="application/pdf"
                                            class="w-full h-full object-cover pointer-events-none opacity-90 group-hover:scale-105 transition-transform duration-500">
                                            <!-- Fallback elegante si el navegador no renderiza el PDF inline -->
                                            <div
                                                class="w-full h-full bg-gradient-to-br from-[#1a1d56] to-[#2a2f85] flex flex-col items-center justify-center text-white p-4">
                                                <FileText class="size-12 text-white/40 mb-2" />
                                                <span
                                                    class="text-xs font-bold uppercase tracking-wider text-white/80">Documento
                                                    PDF</span>
                                            </div>
                                        </object>

                                        <!-- Overlay superior con etiqueta e icono -->
                                        <div
                                            class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-black/60 p-4 flex flex-col justify-between pointer-events-none">
                                            <div class="flex items-center justify-between">
                                                <span
                                                    class="px-3 py-1 bg-[#ae0c26] rounded-full text-[11px] font-black uppercase tracking-wider text-white shadow-md">
                                                    Boletín {{ boletin.numero }}
                                                </span>
                                                <div
                                                    class="size-8 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-white">
                                                    <FileText class="size-4" />
                                                </div>
                                            </div>

                                            <!-- Fecha de publicación formateada correctamente -->
                                            <div
                                                class="flex items-center gap-1.5 text-xs text-white font-medium bg-black/40 backdrop-blur-xs px-2.5 py-1 rounded-lg w-fit">
                                                <Calendar class="size-3.5 text-[#ffc107] shrink-0" />
                                                <span>{{ formatDate(boletin.fecha_publicacion) }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Cuerpo del Boletín -->
                                    <div class="p-6 space-y-3">
                                        <h3
                                            class="font-display font-black text-lg text-[#1a1d56] line-clamp-2 leading-snug">
                                            {{ boletin.titulo }}
                                        </h3>
                                        <p class="text-neutral-600 text-sm leading-relaxed line-clamp-3">
                                            {{ boletin.descripcion || 'Descarga o visualiza el boletín informativocorrespondiente a esta edición.' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Acciones: Ver PDF y Descargar -->
                                <div class="p-6 pt-0 border-t border-neutral-100 mt-4 flex items-center gap-3">
                                    <a :href="'/' + boletin.archivo_pdf" target="_blank"
                                        class="flex-1 bg-[#ae0c26] hover:bg-[#8f091f] text-white font-bold py-2.5 px-4 rounded-xl text-center text-xs uppercase tracking-wider transition-colors flex items-center justify-center gap-2 shadow-sm">
                                        <ExternalLink class="size-4" />
                                        <span>Ver PDF</span>
                                    </a>
                                    <a :href="'/' + boletin.archivo_pdf" download
                                        class="size-10 rounded-xl border border-neutral-200 bg-neutral-50 hover:bg-neutral-100 text-neutral-700 flex items-center justify-center transition-colors shrink-0"
                                        title="Descargar PDF">
                                        <Download class="size-4" />
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Estado Vacío -->
                        <div v-else class="text-center py-16 bg-neutral-50 rounded-3xl border border-neutral-200/80">
                            <FileText class="size-12 text-neutral-400 mx-auto mb-3" />
                            <h3 class="font-display font-bold text-lg text-neutral-700">No hay boletines disponibles por
                                el momento</h3>
                            <p class="text-neutral-500 text-sm">Pronto estaremos publicando las nuevas ediciones.</p>
                        </div>

                        <!-- Paginación Reactiva -->
                        <div v-if="boletines.last_page > 1" class="pt-8 flex justify-center items-center gap-2">
                            <Link v-if="boletines.prev_page_url" :href="boletines.prev_page_url"
                                class="px-4 py-2 rounded-xl border border-neutral-200 bg-white hover:bg-neutral-50 text-neutral-700 text-sm font-bold transition-all flex items-center gap-1">
                                <ChevronLeft class="size-4" />
                                <span>Anterior</span>
                            </Link>

                            <template v-for="(link, i) in boletines.links.slice(1, -1)" :key="i">
                                <Link v-if="link.url" :href="link.url"
                                    class="size-10 rounded-xl text-sm font-bold flex items-center justify-center transition-all"
                                    :class="link.active ? 'bg-[#1a1d56] text-white shadow-md' : 'bg-white border border-neutral-200 text-neutral-700 hover:bg-neutral-50'">
                                    {{ link.label }}
                                </Link>
                            </template>

                            <Link v-if="boletines.next_page_url" :href="boletines.next_page_url"
                                class="px-4 py-2 rounded-xl border border-neutral-200 bg-white hover:bg-neutral-50 text-neutral-700 text-sm font-bold transition-all flex items-center gap-1">
                                <span>Siguiente</span>
                                <ChevronRight class="size-4" />
                            </Link>
                        </div>
                    </div>

                    <!-- Columna Derecha: Sidebar (Contacto y Redes) -->
                    <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-28" data-reveal>
                        <!-- Tarjeta de Atención / Dudas -->
                        <div class="bg-[#1a1d56] rounded-3xl p-8 text-white shadow-xl space-y-4">
                            <div class="size-12 rounded-2xl bg-white/10 flex items-center justify-center text-white">
                                <MessageCircle class="size-6 text-[#ae0c26]" />
                            </div>
                            <h3 class="font-display font-black text-2xl">¿Tienes alguna consulta?</h3>
                            <p class="text-neutral-300 text-sm leading-relaxed">
                                Comunícate directamente con nuestro equipo o visítanos en Jr. Guido 512, Huancayo.
                            </p>
                            <Link href="/contacto"
                                class="inline-flex items-center justify-center w-full bg-[#ae0c26] hover:bg-[#8f091f] text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 text-xs uppercase tracking-wider shadow-md">
                                Ir a Contacto
                            </Link>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </WebLayout>
</template>

<style scoped>
.font-display,
.font-sans {
    font-family: 'Gilroy', ui-sans-serif, system-ui, sans-serif;
}

[data-reveal] {
    opacity: 0;
    transform: translateY(16px);
    transition: opacity 0.7s ease-out, transform 0.7s ease-out;
}

[data-reveal].is-visible {
    opacity: 1;
    transform: translateY(0);
}
</style>