<script setup lang="ts">
import WebLayout from '@/layouts/web/WebLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import { ChevronRight, Cross, Users, Brain } from '@lucide/vue';

defineProps<{
    title?: string;
}>();

const sectionRef = ref<HTMLElement | null>(null);

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

    <Head :title="title || 'Orientación Educativa - Colegio Andino'" />

    <WebLayout>
        <!-- Header con Breadcrumb -->
        <section
            class="relative bg-gradient-to-b from-neutral-100 to-white py-14 md:py-20 border-b border-neutral-200/60 overflow-hidden">
            <!-- Regla decorativa sutil -->
            <div
                class="absolute top-0 left-1/2 -translate-x-1/2 w-24 h-1 bg-gradient-to-r from-[#1a1d56] to-[#ae0c26] rounded-full">
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <span class="text-xs font-bold uppercase tracking-[0.25em] text-[#ae0c26]/80 block mb-3 font-sans">
                    Área Educativa
                </span>
                <h1 class="font-display text-4xl md:text-6xl font-black text-[#1a1d56] tracking-tight mb-4">
                    Orientación Educativa
                </h1>
                <nav aria-label="Breadcrumb"
                    class="flex justify-center items-center gap-2 text-sm font-semibold font-sans">
                    <Link href="/"
                        class="text-[#ae0c26] hover:underline transition-all focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#ae0c26] rounded-sm">
                        Inicio
                    </Link>
                    <span class="text-neutral-400">/</span>
                    <span class="text-neutral-400">Orientación</span>
                    <span class="text-neutral-400">/</span>
                    <span class="text-neutral-700">Orientación Educativa</span>
                </nav>
            </div>
        </section>

        <!-- Sección de Contenido Principal -->
        <section ref="sectionRef" class="py-16 md:py-24 bg-white font-sans">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <!-- Bloque Superior: Título destacado e Introducción -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 md:gap-12 items-center mb-16 md:mb-20" data-reveal>
                    <div class="lg:col-span-6">
                        <span class="text-xs font-black uppercase tracking-[0.2em] text-[#ae0c26] block mb-2">
                            Formación Integral
                        </span>
                        <h2 class="font-display text-4xl md:text-6xl font-black text-[#1a1d56] leading-[1.1]">
                            Guiamos con <br />
                            <span class="italic text-[#ae0c26]">propósito</span>
                        </h2>
                        <div class="w-20 h-1 bg-[#1a1d56] rounded-full mt-4"></div>
                    </div>

                    <div class="lg:col-span-6">
                        <p class="text-neutral-600 text-base md:text-lg leading-relaxed text-justify">
                            El servicio educativo se sustenta en la axiología de la Iglesia Metodista del Perú y en el
                            Currículo Nacional de Educación Básica; por ello nuestro sistema es reconocido gracias a los
                            logros obtenidos por nuestros alumnos en competiciones nacionales e internacionales, así
                            como los ingresos masivos a las más prestigiosas universidades del país.
                        </p>
                    </div>
                </div>

                <!-- Tarjetas de Servicios de Orientación -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 md:gap-10">

                    <!-- Card 1: Capellanía -->
                    <div data-reveal>
                        <Link href="/capellania"
                            class="group relative flex flex-col justify-between h-full min-h-[360px] bg-[#1a1d56] rounded-2xl p-8 shadow-md hover:shadow-2xl hover:-translate-y-1.5 transition-all duration-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                            <div>
                                <div class="w-14 h-14 rounded-xl bg-white/10 flex items-center justify-center mb-6">
                                    <Cross class="size-7 text-white" />
                                </div>
                                <h3 class="font-display text-2xl md:text-3xl font-black text-white mb-4">
                                    Capellanía
                                </h3>
                                <p class="text-neutral-200 text-sm md:text-base leading-relaxed">
                                    La Capellanía es un servicio escolar, cuyo objetivo es promover una formación
                                    integral de los miembros de la Comunidad Educativa.
                                </p>
                            </div>

                            <div class="flex items-center justify-between pt-6 border-t border-white/10 mt-6">
                                <span
                                    class="text-xs font-bold uppercase tracking-wider text-amber-400 group-hover:text-white transition-colors">
                                    Conocer más
                                </span>

                                <!-- Círculo contenedor con tamaño fijo (w-12 h-12 = 48px) -->
                                <div
                                    class="w-12 h-12 min-w-12 min-h-12 shrink-0 aspect-square rounded-full border-2 border-white/20 bg-white/5 flex items-center justify-center text-white group-hover:bg-[#ae0c26] group-hover:border-[#ae0c26] group-hover:scale-105 transition-all duration-300 shadow-md">
                                    <!-- Ícono pequeño y proporcional (size-4 = 16px) -->
                                    <ChevronRight
                                        class="size-4 shrink-0 group-hover:translate-x-0.5 transition-transform duration-300" />
                                </div>
                            </div>
                        </Link>
                    </div>

                    <!-- Card 2: Actividades -->
                    <div data-reveal>
                        <Link href="/actividades"
                            class="group relative flex flex-col justify-between h-full min-h-[360px] bg-[#1a1d56] rounded-2xl p-8 shadow-md hover:shadow-2xl hover:-translate-y-1.5 transition-all duration-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                            <div>
                                <div class="w-14 h-14 rounded-xl bg-white/10 flex items-center justify-center mb-6">
                                    <Users class="size-7 text-white" />
                                </div>
                                <h3 class="font-display text-2xl md:text-3xl font-black text-white mb-4">
                                    Actividades
                                </h3>
                                <p class="text-neutral-200 text-sm md:text-base leading-relaxed">
                                    Su misión es planear, organizar, dirigir y controlar en conjunto con las áreas
                                    involucradas las actividades culturales, artísticas y deportivas.
                                </p>
                            </div>

                            <div class="flex items-center justify-between pt-6 border-t border-white/10 mt-6">
                                <span
                                    class="text-xs font-bold uppercase tracking-wider text-amber-400 group-hover:text-white transition-colors">
                                    Conocer más
                                </span>

                                <!-- Círculo contenedor con tamaño fijo (w-12 h-12 = 48px) -->
                                <div
                                    class="w-12 h-12 min-w-12 min-h-12 shrink-0 aspect-square rounded-full border-2 border-white/20 bg-white/5 flex items-center justify-center text-white group-hover:bg-[#ae0c26] group-hover:border-[#ae0c26] group-hover:scale-105 transition-all duration-300 shadow-md">
                                    <!-- Ícono pequeño y proporcional (size-4 = 16px) -->
                                    <ChevronRight
                                        class="size-4 shrink-0 group-hover:translate-x-0.5 transition-transform duration-300" />
                                </div>
                            </div>
                        </Link>
                    </div>

                    <!-- Card 3: Departamento Psicológico -->
                    <div class="md:col-span-2 lg:col-span-1" data-reveal>
                        <Link href="/psicologico"
                            class="group relative flex flex-col justify-between h-full min-h-[360px] bg-[#1a1d56] rounded-2xl p-8 shadow-md hover:shadow-2xl hover:-translate-y-1.5 transition-all duration-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                            <div>
                                <div class="w-14 h-14 rounded-xl bg-white/10 flex items-center justify-center mb-6">
                                    <Brain class="size-7 text-white" />
                                </div>
                                <h3 class="font-display text-2xl md:text-3xl font-black text-white mb-4">
                                    Dpto. Psicológico
                                </h3>
                                <p class="text-neutral-200 text-sm md:text-base leading-relaxed">
                                    Tiene por objetivo el asesoramiento y apoyo especializado del desarrollo
                                    psicológico, cognitivo, social y afectivo, tanto dentro como fuera del Colegio.
                                </p>
                            </div>

                            <div class="flex items-center justify-between pt-6 border-t border-white/10 mt-6">
                                <span
                                    class="text-xs font-bold uppercase tracking-wider text-amber-400 group-hover:text-white transition-colors">
                                    Conocer más
                                </span>

                                <!-- Círculo contenedor con tamaño fijo (w-12 h-12 = 48px) -->
                                <div
                                    class="w-12 h-12 min-w-12 min-h-12 shrink-0 aspect-square rounded-full border-2 border-white/20 bg-white/5 flex items-center justify-center text-white group-hover:bg-[#ae0c26] group-hover:border-[#ae0c26] group-hover:scale-105 transition-all duration-300 shadow-md">
                                    <!-- Ícono pequeño y proporcional (size-4 = 16px) -->
                                    <ChevronRight
                                        class="size-4 shrink-0 group-hover:translate-x-0.5 transition-transform duration-300" />
                                </div>
                            </div>
                        </Link>
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