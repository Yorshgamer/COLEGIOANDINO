<script setup lang="ts">
import WebLayout from '@/layouts/web/WebLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref, computed } from 'vue';
import { ChevronLeft, ChevronRight } from '@lucide/vue';

defineProps<{
    title?: string;
}>();

const sectionRef = ref<HTMLElement | null>(null);

// Imágenes de la galería de actividades
const galleryImages = [
    '/img/actividades/DSC05685 (1).jpg',
    '/img/actividades/DSC05695.jpg',
    '/img/actividades/DSC05720.jpg',
    '/img/actividades/DSC05725.jpg',
    '/img/actividades/DSC05752.jpg',
    '/img/actividades/DSC05780.jpg',
    '/img/actividades/DSC05792.jpg',
    '/img/actividades/DSC05810.jpg',
    '/img/actividades/DSC05824.jpg',
    '/img/actividades/DSC05848.jpg',
    '/img/actividades/DSC05864.jpg',
    '/img/actividades/DSC05878.jpg',
    '/img/actividades/DSC05885.jpg',
    '/img/actividades/DSC05895.jpg',
    '/img/actividades/DSC05900.jpg',
    '/img/actividades/DSC05911.jpg',
    '/img/actividades/DSC05916.jpg',
    '/img/actividades/DSC05919.jpg',
    '/img/actividades/DSC05920.jpg',
    '/img/actividades/DSC05924.jpg',
];

// Lógica del Carrusel
const currentIndex = ref(0);
const itemsPerPage = 3;

const totalSlides = computed(() => Math.ceil(galleryImages.length / itemsPerPage));

const currentSlideImages = computed(() => {
    const start = currentIndex.value * itemsPerPage;
    return galleryImages.slice(start, start + itemsPerPage);
});

const nextSlide = () => {
    currentIndex.value = (currentIndex.value + 1) % totalSlides.value;
};

const prevSlide = () => {
    currentIndex.value = (currentIndex.value - 1 + totalSlides.value) % totalSlides.value;
};

onMounted(() => {
    const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (prefersReduced || !sectionRef.value) return;

    const targets = sectionRef.value.querySelectorAll('[data-reveal]');
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
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

    <Head :title="title || 'Actividades - Colegio Andino'" />

    <WebLayout>
        <!-- Header con Breadcrumb -->
        <section
            class="relative bg-gradient-to-b from-neutral-100 to-white py-14 md:py-20 border-b border-neutral-200/60 overflow-hidden">
            <div
                class="absolute top-0 left-1/2 -translate-x-1/2 w-24 h-1 bg-gradient-to-r from-[#1a1d56] to-[#ae0c26] rounded-full">
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <span class="text-xs font-bold uppercase tracking-[0.25em] text-[#ae0c26]/80 block mb-3 font-sans">
                    Vida Escolar
                </span>
                <h1 class="font-display text-4xl md:text-6xl font-black text-[#1a1d56] tracking-tight mb-4">
                    Actividades
                </h1>
                <nav aria-label="Breadcrumb"
                    class="flex justify-center items-center gap-2 text-sm font-semibold font-sans">
                    <Link href="/"
                        class="text-[#ae0c26] hover:underline transition-all focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#ae0c26] rounded-sm">
                        Inicio
                    </Link>
                    <span class="text-neutral-400">/</span>
                    <Link href="/orientacion-educativa" class="text-[#1a1d56] hover:text-[#ae0c26] transition-all">
                        Orientación Educativa
                    </Link>
                    <span class="text-neutral-400">/</span>
                    <span class="text-neutral-500">Actividades</span>
                </nav>
            </div>
        </section>

        <!-- Sección de Texto Introductorio -->
        <section ref="sectionRef" class="py-16 md:py-20 bg-white font-sans">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center" data-reveal>
                <span class="text-xs font-black uppercase tracking-[0.2em] text-[#ae0c26] block mb-2">
                    Resumen Anual
                </span>
                <h2 class="font-display text-3xl md:text-5xl font-black text-[#1a1d56] mb-6">
                    Clausura Escolar <span class="text-[#ae0c26]">2025</span>
                </h2>

                <div class="text-neutral-600 text-base md:text-lg leading-relaxed space-y-4 max-w-3xl mx-auto">
                    <p>
                        Finalizamos un año escolar lleno de aprendizaje y grandes experiencias. Aquí les dejamos un
                        resumen fotográfico de lo que fue nuestra ceremonia de clausura.
                    </p>
                    <p>
                        Gracias a los padres de familia por confiar en nosotros y a nuestros alumnos por su dedicación
                        constante.
                    </p>
                    <p class="font-bold text-[#1a1d56] pt-2">
                        ¡Nos vemos el próximo año para seguir creciendo y soñando juntos!
                    </p>
                </div>
            </div>

            <!-- Galería Carrusel de Imágenes -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 md:mt-16" data-reveal>
                <div class="relative">
                    <!-- Grid de 3 Imágenes del Slide Actual -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div v-for="(img, idx) in currentSlideImages" :key="idx"
                            class="relative rounded-2xl overflow-hidden shadow-lg border border-neutral-100 group aspect-[4/3] bg-neutral-900">

                            <!-- Zoom scale-125 + object-bottom para desplazar la vista hacia abajo y recortar la esquina superior derecha -->
                            <img :src="img" alt="Clausura Escolar Colegio Andino"
                                class="w-full h-full object-cover object-bottom scale-[1.22] group-hover:scale-[1.28] transition-transform duration-500 ease-out" />

                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Navegación del Carrusel -->
                    <div class="flex items-center justify-between mt-8">
                        <button @click="prevSlide"
                            class="w-12 h-12 rounded-full border border-neutral-200 bg-white flex items-center justify-center text-[#1a1d56] hover:bg-[#ae0c26] hover:text-white hover:border-[#ae0c26] transition-all duration-300 shadow-md">
                            <ChevronLeft class="size-6" />
                        </button>

                        <!-- Indicadores de Páginas -->
                        <div class="flex items-center gap-2">
                            <span v-for="n in totalSlides" :key="n" @click="currentIndex = n - 1"
                                class="h-2.5 rounded-full cursor-pointer transition-all duration-300"
                                :class="(n - 1) === currentIndex ? 'w-8 bg-[#ae0c26]' : 'w-2.5 bg-neutral-300 hover:bg-neutral-400'">
                            </span>
                        </div>

                        <button @click="nextSlide"
                            class="w-12 h-12 rounded-full border border-neutral-200 bg-white flex items-center justify-center text-[#1a1d56] hover:bg-[#ae0c26] hover:text-white hover:border-[#ae0c26] transition-all duration-300 shadow-md">
                            <ChevronRight class="size-6" />
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </WebLayout>
</template>

<style scoped>
/* Tipografía Gilroy unificada */
.font-display,
.font-sans {
    font-family: 'Gilroy', ui-sans-serif, system-ui, sans-serif;
}

/* Animaciones de revelado por scroll */
[data-reveal] {
    opacity: 0;
    transform: translateY(16px);
    transition: opacity 0.7s ease-out, transform 0.7s ease-out;
}

[data-reveal].is-visible {
    opacity: 1;
    transform: translateY(0);
}

@media (prefers-reduced-motion: reduce) {
    [data-reveal] {
        opacity: 1;
        transform: none;
        transition: none;
    }
}
</style>