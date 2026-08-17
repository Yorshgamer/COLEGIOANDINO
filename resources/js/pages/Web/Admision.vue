<script setup lang="ts">
import WebLayout from '@/layouts/web/WebLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import { CheckCircle2 } from '@lucide/vue';

defineProps<{
    title?: string;
}>();

const sectionRef = ref<HTMLElement | null>(null);

const requisitosInicial = [
    'Solicitud de admisión (Anexo 1)',
    'Ficha de datos (Anexo 2)',
    'Escaneo de Acta de nacimiento (Nítido)',
    'Escaneo del DNI del postulante (Nítido)',
    'Escaneo de la tarjeta de control de crecimiento y vacunas',
    'Ficha psicopedagógica (Anexo 3)',
    'Una fotografía de cuerpo entero reciente del postulante, realizando una actividad, tomada dentro del mes de la solicitud.',
];

const requisitosGrados = [
    'Solicitud de admisión (Anexo 1)',
    'Ficha de datos (Anexo 2)',
    'Escaneo de Acta de nacimiento (Nítido)',
    'Escaneo del DNI del postulante (Nítido)',
    'Escaneo de la tarjeta de control de crecimiento y vacunas',
    'Ficha psicopedagógica (Anexo 3)',
    'Una fotografía de cuerpo entero reciente del postulante, realizando una actividad, tomada dentro del mes de la solicitud.',
];

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
    <Head :title="title || 'Admisión 2026 - Colegio Andino'" />

    <WebLayout>
        <!-- Hero / Banner Principal -->
        <section class="relative min-h-[550px] md:min-h-[620px] flex items-center bg-[#1a1d56] overflow-hidden">
            <!-- Barra superior roja -->
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-[#ae0c26] z-30"></div>

            <!-- Fondo de imagen con Overlay en gradiente -->
            <div class="absolute inset-0 z-0">
                <img src="/img/admision/fondo.jpg" alt="Fondo Admisión Colegio Andino" class="w-full h-full object-cover object-center" />
                <div class="absolute inset-0 bg-gradient-to-r from-[#1a1d56] via-[#1a1d56]/90 to-[#1a1d56]/40"></div>
            </div>

            <!-- Contenido del Banner -->
            <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20 w-full">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    
                    <!-- Lado Izquierdo: Título gráfico -->
                    <div class="lg:col-span-7 text-center lg:text-left">
                        <img src="/img/admision/titulo.png" alt="Admisión 2026" class="w-full max-w-xl mx-auto lg:mx-0 h-auto object-contain drop-shadow-xl" />
                    </div>

                    <!-- Lado Derecho: Imagen de Estudiante -->
                    <div class="lg:col-span-5 flex justify-center">
                        <img src="/img/admision/right.png" alt="Estudiante Colegio Andino" class="w-full max-w-md h-auto max-h-[500px] object-contain drop-shadow-2xl" />
                    </div>

                </div>
            </div>
        </section>

        <div ref="sectionRef">
            <!-- Sección Únete a la Familia Andino -->
            <section class="py-16 md:py-24 bg-white font-sans">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    
                    <div class="text-center mb-12" data-reveal>
                        <span class="text-xs font-black uppercase tracking-[0.2em] text-[#ae0c26] block mb-2">
                            Proceso de Selección
                        </span>
                        <h2 class="font-display text-3xl md:text-5xl font-black text-[#1a1d56]">
                            ¡Únete a la <span class="text-[#ae0c26]">Familia Andino</span>!
                        </h2>
                        <div class="w-20 h-1 bg-[#1a1d56] mx-auto rounded-full mt-4"></div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                        <!-- Imagen Ilustrativa -->
                        <div class="lg:col-span-5" data-reveal>
                            <div class="relative rounded-3xl overflow-hidden shadow-xl border border-neutral-100 group aspect-[4/3] md:aspect-[5/4]">
                                <img src="/img/admision/left.jpg" alt="Estudiante en biblioteca" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                            </div>
                        </div>

                        <!-- Texto Informativo -->
                        <div class="lg:col-span-7" data-reveal>
                            <div class="text-neutral-600 text-base md:text-lg leading-relaxed space-y-6 text-justify">
                                <p>
                                    Nuestra meta es clara: brindar una educación integral que forme <strong class="text-[#1a1d56] font-bold">líderes con valores cristianos</strong>, capaces de transformar el mundo.
                                </p>

                                <p>
                                    Ofrecemos un entorno seguro donde la curiosidad y la investigación son los motores del aprendizaje. Desde el nivel <strong class="text-neutral-800">Inicial hasta Primaria y Secundaria</strong>, acompañamos a nuestros estudiantes en la construcción de su autonomía, fomentando el pensamiento crítico y la empatía en cada paso que dan.
                                </p>

                                <div class="p-6 bg-neutral-50 rounded-2xl border border-neutral-200/80 shadow-xs">
                                    <p class="font-bold text-[#1a1d56] text-lg text-center md:text-left">
                                        ¡Asegura el futuro de tus hijos hoy mismo!
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </section>

           <!-- Sección Requisitos Inicial 3 años -->
            <section class="py-16 bg-neutral-50/60 font-sans border-t border-neutral-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    
                    <div class="mb-10" data-reveal>
                        <h3 class="font-display text-2xl md:text-4xl font-black text-[#1a1d56] inline-block border-b-4 border-[#ae0c26] pb-2">
                            Inicial 3 años
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                        <!-- Lista de Requisitos en Tarjeta Azul Suave -->
                        <div class="lg:col-span-8" data-reveal>
                            <div class="bg-[#e8f0fe]/70 border border-blue-100 rounded-3xl p-6 sm:p-8 md:p-10 shadow-xs">
                                <ul class="space-y-4 text-neutral-800 text-sm md:text-base leading-relaxed">
                                    <li v-for="(req, idx) in requisitosInicial" :key="idx" class="flex items-start gap-3">
                                        <CheckCircle2 class="size-5 text-[#1a1d56] shrink-0 mt-0.5" />
                                        <span>{{ req }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Íconos Ilustrativos (Más grandes y responsivos) -->
                        <div class="lg:col-span-4" data-reveal>
                            <div class="grid grid-cols-3 lg:grid-cols-1 gap-4 sm:gap-6 items-center justify-center">
                                <div class="flex justify-center p-2">
                                    <img src="/img/admision/admision.png" alt="Admisión" class="w-auto h-28 sm:h-32 lg:h-36 max-w-full object-contain transition-transform hover:scale-105 duration-300 drop-shadow-sm" />
                                </div>
                                <div class="flex justify-center p-2">
                                    <img src="/img/admision/ficha.png" alt="Ficha" class="w-auto h-28 sm:h-32 lg:h-36 max-w-full object-contain transition-transform hover:scale-105 duration-300 drop-shadow-sm" />
                                </div>
                                <div class="flex justify-center p-2">
                                    <img src="/img/admision/pedagojica.png" alt="Pedagógica" class="w-auto h-28 sm:h-32 lg:h-36 max-w-full object-contain transition-transform hover:scale-105 duration-300 drop-shadow-sm" />
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </section>

            <!-- Sección Requisitos 2do a 10mo grado -->
            <section class="py-16 bg-white font-sans border-t border-neutral-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    
                    <div class="mb-10" data-reveal>
                        <h3 class="font-display text-2xl md:text-4xl font-black text-[#1a1d56] inline-block border-b-4 border-[#ae0c26] pb-2">
                            2do a 10mo grado
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                        <!-- Íconos Ilustrativos (Izquierda en Desktop / Más grandes) -->
                        <div class="lg:col-span-4 order-2 lg:order-1" data-reveal>
                            <div class="grid grid-cols-2 lg:grid-cols-1 gap-6 items-center justify-center">
                                <div class="flex justify-center p-2">
                                    <img src="/img/admision/vacante.png" alt="Vacante" class="w-auto h-28 sm:h-32 lg:h-36 max-w-full object-contain transition-transform hover:scale-105 duration-300 drop-shadow-sm" />
                                </div>
                                <div class="flex justify-center p-2">
                                    <img src="/img/admision/ficha.png" alt="Ficha" class="w-auto h-28 sm:h-32 lg:h-36 max-w-full object-contain transition-transform hover:scale-105 duration-300 drop-shadow-sm" />
                                </div>
                            </div>
                        </div>

                        <!-- Lista de Requisitos (Derecha en Desktop) -->
                        <div class="lg:col-span-8 order-1 lg:order-2" data-reveal>
                            <div class="bg-[#e8f0fe]/70 border border-blue-100 rounded-3xl p-6 sm:p-8 md:p-10 shadow-xs">
                                <ul class="space-y-4 text-neutral-800 text-sm md:text-base leading-relaxed">
                                    <li v-for="(req, idx) in requisitosGrados" :key="idx" class="flex items-start gap-3">
                                        <CheckCircle2 class="size-5 text-[#1a1d56] shrink-0 mt-0.5" />
                                        <span>{{ req }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </section>

            <!-- Sección Video Promocional -->
            <section class="py-16 md:py-24 bg-[#ae0c26] font-sans text-white" data-reveal>
                <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <span class="text-xs font-black uppercase tracking-[0.2em] text-white/80 block mb-2">
                        Video Institucional
                    </span>
                    <h2 class="font-display text-3xl md:text-5xl font-black mb-10">
                        Propuesta 2026
                    </h2>

                    <div class="relative aspect-video w-full rounded-2xl overflow-hidden shadow-2xl bg-black border border-white/10">
                        <iframe
                            src="https://player.vimeo.com/video/1149954897?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479"
                            class="absolute inset-0 w-full h-full"
                            frameborder="0"
                            allow="autoplay; fullscreen; picture-in-picture; clipboard-write; encrypted-media; web-share"
                            referrerpolicy="strict-origin-when-cross-origin"
                            title="VIDEO REBRANDING - COLEGIO ANDINO">
                        </iframe>
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