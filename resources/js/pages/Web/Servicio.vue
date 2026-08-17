<script setup lang="ts">
import WebLayout from '@/layouts/web/WebLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted, ref, computed } from 'vue';
import { ArrowRight, Phone, ChevronLeft, ChevronRight } from '@lucide/vue';

defineProps<{
    title?: string;
}>();

const sectionRef = ref<HTMLElement | null>(null);

// Galería de imágenes del Teatro/Auditorio
const galleryImages = [
    '/img/teatro/DSC00467.jpg',
    '/img/teatro/DSC00372.jpg',
    '/img/teatro/DSC00302.jpg',
    '/img/teatro/603874354_122188527674481352_3098046897185726297_n.jpg',
    '/img/teatro/603061587_122188527920481352_5501888679337851971_n.jpg',
    '/img/teatro/603058595_122188527866481352_370204960188099500_n.jpg',
    '/img/teatro/559646510_122176002716481352_8651349197550786965_n.jpg',
    '/img/actividades/DSC05695.jpg',
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
    <Head :title="title || 'Nuestros Servicios - Colegio Andino'" />

    <WebLayout>
        <!-- Hero / Banner Principal -->
        <section class="relative min-h-[500px] md:min-h-[580px] flex items-center bg-[#1a1d56] overflow-hidden">
            <!-- Barra superior roja -->
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-[#ae0c26] z-30"></div>

            <!-- Fondo de imagen con Overlay -->
            <div class="absolute inset-0 z-0">
                <img src="/img/teatro/WhatsApp Image 2026-01-18 at 11.17.45 PM.jpeg" alt="Auditorio Colegio Andino" class="w-full h-full object-cover object-center" />
                <div class="absolute inset-0 bg-[#1a1d56]/75"></div>
            </div>

            <!-- Contenido del Banner -->
            <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center py-20 w-full">
                <h1 class="font-display text-4xl sm:text-6xl lg:text-7xl font-black text-white leading-tight mb-4 drop-shadow-lg">
                    Infraestructura de Excelencia
                </h1>
                <p class="font-sans text-xl sm:text-2xl lg:text-3xl font-semibold text-neutral-100 drop-shadow-md max-w-3xl mx-auto">
                    al Servicio de la Comunidad
                </p>
            </div>
        </section>

        <div ref="sectionRef">
            <!-- Sección Información Teatro + Video -->
            <section class="py-16 md:py-24 bg-white font-sans">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    
                    <!-- Título principal -->
                    <div class="text-center mb-12 md:mb-16" data-reveal>
                        <span class="text-xs font-black uppercase tracking-[0.2em] text-[#ae0c26] block mb-2">
                            Espacios Institucionales
                        </span>
                        <h2 class="font-display text-3xl md:text-5xl font-black text-[#1a1d56]">
                            El Escenario Ideal para tus Grandes Momentos
                        </h2>
                        <div class="w-20 h-1 bg-[#1a1d56] mx-auto rounded-full mt-4"></div>
                    </div>

                    <!-- Contenido a 2 columnas -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                        
                        <!-- Columna Izquierda: Puntos informativos -->
                        <div class="lg:col-span-6 space-y-6" data-reveal>
                            <p class="text-neutral-600 text-base md:text-lg leading-relaxed text-justify">
                                Ponemos a tu disposición una infraestructura moderna y versátil en el corazón de Huancayo, diseñada para dar vida a eventos que inspiran.
                            </p>

                            <!-- Teatro Profesional -->
                            <div class="flex items-start gap-4 p-4 rounded-2xl bg-neutral-50 border border-neutral-100 transition-all hover:border-neutral-200">
                                <div class="w-10 h-10 rounded-full bg-[#ae0c26] text-white flex items-center justify-center shrink-0 mt-1 shadow-md">
                                    <ArrowRight class="size-5" />
                                </div>
                                <div>
                                    <h3 class="font-display font-bold text-[#ae0c26] text-lg md:text-xl mb-1">
                                        Teatro Profesional:
                                    </h3>
                                    <p class="text-neutral-600 text-sm md:text-base leading-relaxed text-justify">
                                        Escenario con acústica e iluminación técnica, ideal para recitales, conferencias y artes escénicas.
                                    </p>
                                </div>
                            </div>

                            <!-- Complejo Deportivo -->
                            <div class="flex items-start gap-4 p-4 rounded-2xl bg-neutral-50 border border-neutral-100 transition-all hover:border-neutral-200">
                                <div class="w-10 h-10 rounded-full bg-[#ae0c26] text-white flex items-center justify-center shrink-0 mt-1 shadow-md">
                                    <ArrowRight class="size-5" />
                                </div>
                                <div>
                                    <h3 class="font-display font-bold text-[#ae0c26] text-lg md:text-xl mb-1">
                                        Complejo Deportivo:
                                    </h3>
                                    <p class="text-neutral-600 text-sm md:text-base leading-relaxed text-justify">
                                        Losas polideportivas amplias y seguras para torneos, clínicas deportivas y jornadas de integración.
                                    </p>
                                </div>
                            </div>

                            <!-- Patios Versátiles -->
                            <div class="flex items-start gap-4 p-4 rounded-2xl bg-neutral-50 border border-neutral-100 transition-all hover:border-neutral-200">
                                <div class="w-10 h-10 rounded-full bg-[#ae0c26] text-white flex items-center justify-center shrink-0 mt-1 shadow-md">
                                    <ArrowRight class="size-5" />
                                </div>
                                <div>
                                    <h3 class="font-display font-bold text-[#ae0c26] text-lg md:text-xl mb-1">
                                        Patios Versátiles:
                                    </h3>
                                    <p class="text-neutral-600 text-sm md:text-base leading-relaxed text-justify">
                                        Áreas abiertas de gran capacidad, perfectas para ferias culturales, exhibiciones y encuentros comunitarios.
                                    </p>
                                </div>
                            </div>

                            <!-- Información de Alquiler / Celular -->
                            <div class="flex items-center gap-4 p-5 rounded-2xl bg-[#1a1d56] text-white shadow-lg mt-6">
                                <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center text-white shrink-0">
                                    <Phone class="size-6" />
                                </div>
                                <div>
                                    <span class="text-xs font-bold uppercase tracking-wider text-white/70 block">Informes y Reservas</span>
                                    <a href="tel:979915892" class="font-display font-black text-xl md:text-2xl text-white hover:text-[#ae0c26] transition-colors">
                                        979 915 892
                                    </a>
                                </div>
                            </div>

                        </div>

                        <!-- Columna Derecha: Reproductor de Video -->
                        <div class="lg:col-span-6" data-reveal>
                            <div class="relative aspect-video w-full rounded-3xl overflow-hidden shadow-2xl bg-black border border-neutral-200">
                                <iframe
                                    src="https://player.vimeo.com/video/1160254710?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479"
                                    class="absolute inset-0 w-full h-full"
                                    frameborder="0"
                                    allow="autoplay; fullscreen; picture-in-picture; clipboard-write; encrypted-media; web-share"
                                    referrerpolicy="strict-origin-when-cross-origin"
                                    title="VIDEO AUDITORIO - COLEGIO ANDINO">
                                </iframe>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            <!-- Sección Carrusel / Galería de Fotos -->
            <section class="py-16 md:py-24 bg-neutral-50/70 font-sans border-t border-neutral-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" data-reveal>
                    <div class="text-center mb-12">
                        <span class="text-xs font-black uppercase tracking-[0.2em] text-[#ae0c26] block mb-2">
                            Galería Fotográfica
                        </span>
                        <h2 class="font-display text-3xl md:text-4xl font-black text-[#1a1d56]">
                            Nuestras Instalaciones
                        </h2>
                    </div>

                    <div class="relative">
                        <!-- Grid de 3 Imágenes del Slide Actual -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div v-for="(img, idx) in currentSlideImages" :key="idx"
                                class="relative rounded-2xl overflow-hidden shadow-lg border border-neutral-100 group aspect-[4/3]">
                                <img :src="img" alt="Teatro Colegio Andino"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                                <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>
                        </div>

                        <!-- Botones de Navegación -->
                        <div class="flex items-center justify-between mt-8">
                            <button @click="prevSlide"
                                class="w-12 h-12 rounded-full border border-neutral-200 bg-white flex items-center justify-center text-[#1a1d56] hover:bg-[#ae0c26] hover:text-white hover:border-[#ae0c26] transition-all duration-300 shadow-md">
                                <ChevronLeft class="size-6" />
                            </button>

                            <!-- Indicadores -->
                            <div class="flex items-center gap-2">
                                <span v-for="n in totalSlides" :key="n"
                                    @click="currentIndex = n - 1"
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
        </div>
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