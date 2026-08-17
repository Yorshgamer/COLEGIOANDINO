<script setup lang="ts">
import WebLayout from '@/layouts/web/WebLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

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

    <Head :title="title || 'Misión y Visión - Colegio Andino'" />

    <WebLayout>
        <!-- Header con Breadcrumb -->
        <section
            class="relative bg-gradient-to-b from-neutral-100 to-white py-14 md:py-20 border-b border-neutral-200/60 overflow-hidden">
            <div
                class="absolute top-0 left-1/2 -translate-x-1/2 w-24 h-1 bg-gradient-to-r from-[#1a1d56] to-[#ae0c26] rounded-full">
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <span class="text-xs font-bold uppercase tracking-[0.25em] text-[#ae0c26]/80 block mb-3 font-sans">
                    Nuestra Identidad
                </span>
                <h1 class="font-display text-4xl md:text-6xl font-black text-[#1a1d56] tracking-tight mb-4">
                    Misión y Visión
                </h1>
                <nav aria-label="Breadcrumb"
                    class="flex justify-center items-center gap-2 text-sm font-semibold font-sans">
                    <Link href="/"
                        class="text-[#ae0c26] hover:underline transition-all focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#ae0c26] rounded-sm">
                        Inicio
                    </Link>
                    <span class="text-neutral-400">/</span>
                    <span class="text-neutral-400">¿Quiénes somos?</span>
                    <span class="text-neutral-400">/</span>
                    <span class="text-neutral-700">Misión y Visión</span>
                </nav>
            </div>
        </section>

        <!-- Sección de 3 Tarjetas (Visión, Misión, Principios) -->
        <section ref="sectionRef" class="py-8 md:py-10 bg-white font-sans">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-10">

                    <!-- Tarjeta 1 -->
                    <div class="flex flex-col items-center text-center group" data-reveal>

                        <div class="relative flex justify-center mb-1">
                            <div
                                class="absolute w-80 h-80 md:w-[38rem] md:h-[38rem] rounded-full bg-[#1a1d56]/5 blur-3xl -z-10 group-hover:bg-[#1a1d56]/10 transition-colors duration-300">
                            </div>

                            <img class="w-80 h-80 md:w-[35rem] md:h-[35rem] object-contain drop-shadow-md group-hover:scale-105 transition-transform duration-300"
                                src="/img/quienes/vision.png" alt="Visión Colegio Andino">
                        </div>

                        <h2 class="font-display text-2xl md:text-3xl font-black text-[#1a1d56] mb-2">
                            Visión
                        </h2>

                        <p class="text-neutral-600 text-sm md:text-base leading-relaxed text-justify max-w-md">
                            Para el año 2030, ser una institución educativa acreditada por su excelencia académica,
                            formativa y organizacional; reconocida por formar estudiantes íntegros, líderes y agentes de
                            cambio en su comunidad, comprometidos con la responsabilidad social y ambiental, guiados por
                            principios éticos y valores cristianos.
                        </p>

                    </div>

                    <!-- Tarjeta 2 -->
                    <div class="flex flex-col items-center text-center group" data-reveal>

                        <div class="relative flex justify-center mb-1">
                            <div
                                class="absolute w-80 h-80 md:w-[38rem] md:h-[38rem] rounded-full bg-[#ae0c26]/5 blur-3xl -z-10 group-hover:bg-[#ae0c26]/10 transition-colors duration-300">
                            </div>

                            <img class="w-80 h-80 md:w-[35rem] md:h-[35rem] object-contain drop-shadow-md group-hover:scale-105 transition-transform duration-300"
                                src="/img/quienes/mision.png" alt="Misión Colegio Andino">
                        </div>

                        <h2 class="font-display text-2xl md:text-3xl font-black text-[#ae0c26] mb-2">
                            Misión
                        </h2>

                        <p class="text-neutral-600 text-sm md:text-base leading-relaxed text-justify max-w-md">
                            Brindamos una educación integral, inclusiva y de excelencia a los estudiantes de Huancayo y
                            la región Junín, fomentando el desarrollo de competencias, valores éticos y cristianos,
                            pensamiento crítico y el uso responsable de la tecnología, para formar ciudadanos
                            comprometidos con su comunidad y con el desarrollo sostenible del país.
                        </p>

                    </div>

                    <!-- Tarjeta 3 -->
                    <div class="flex flex-col items-center text-center md:col-span-2 lg:col-span-1 md:max-w-lg md:mx-auto lg:max-w-none group"
                        data-reveal>

                        <div class="relative flex justify-center mb-1">
                            <div
                                class="absolute w-80 h-80 md:w-[38rem] md:h-[38rem] rounded-full bg-neutral-800/5 blur-3xl -z-10 group-hover:bg-neutral-800/10 transition-colors duration-300">
                            </div>

                            <img class="w-80 h-80 md:w-[35rem] md:h-[35rem] object-contain drop-shadow-md group-hover:scale-105 transition-transform duration-300"
                                src="/img/quienes/valores.png" alt="Principios Colegio Andino">
                        </div>

                        <h2 class="font-display text-2xl md:text-3xl font-black text-neutral-700 mb-2">
                            Principios
                        </h2>

                        <p class="text-neutral-600 text-sm md:text-base leading-relaxed text-justify max-w-md">
                            Nuestra labor educativa se fundamenta en el <strong class="text-[#ae0c26]">Amor</strong>,
                            que orienta las relaciones desde el respeto y servicio; la <strong
                                class="text-[#1a1d56]">Cultura</strong> como expresión de identidad y pertenencia
                            impulsando el aprendizaje transformador; y la <strong
                                class="text-neutral-800">Humanidad</strong> como el compromiso con la dignidad de toda
                            persona para construir una sociedad justa y fraterna.
                        </p>

                    </div>

                </div>

            </div>
        </section>
    </WebLayout>
</template>

<style scoped>
/* Tipografía Gilroy unificada para títulos y textos */
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