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

    <Head :title="title || 'Coordinación Académica - Colegio Andino'" />

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
                    Coordinación Académica
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
                    <span class="text-neutral-700">Coordinación Académica</span>
                </nav>
            </div>
        </section>

        <!-- Sección de Contenido Principal -->
        <section ref="sectionRef" class="py-16 md:py-24 bg-white font-sans">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 md:gap-16 items-center">

                    <!-- Imagen a la izquierda (Cuadro redondeado con relleno total) -->
                    <div class="lg:col-span-6 lg:sticky lg:top-24" data-reveal>
                        <div class="relative w-full max-w-lg mx-auto">
                            <!-- Resplandor / sombra trasera -->
                            <div
                                class="absolute -inset-2 bg-gradient-to-br from-[#1a1d56] to-[#ae0c26] rounded-3xl opacity-20 blur-lg">
                            </div>

                            <!-- Marco contenedor principal -->
                            <div
                                class="relative w-full aspect-[4/3] rounded-3xl overflow-hidden shadow-xl border border-neutral-100 bg-[#1a1d56]">
                                <!-- La clase scale-108 hace zoom para recortar los márgenes blancos que vienen dentro del PNG -->
                                <img class="w-full h-full object-cover scale-[1.08] group-hover:scale-115 transition-transform duration-500 ease-out"
                                    src="/img/propuesta/coordinacion.png"
                                    alt="Coordinación Académica - Orquesta Estudiantil Colegio Andino" />
                            </div>
                        </div>
                    </div>

                    <!-- Texto a la derecha -->
                    <div class="lg:col-span-6" data-reveal>
                        <div class="space-y-6">

                            <!-- Antetítulo y Título -->
                            <div>
                                <span class="text-xs font-black uppercase tracking-[0.2em] text-[#ae0c26] block mb-2">
                                    Excelencia Pedagógica
                                </span>
                                <h2 class="font-display text-3xl md:text-5xl font-black text-[#1a1d56] leading-[1.15]">
                                    Más que coordinar, conectamos mentes, <span class="italic text-[#ae0c26]">ideas y
                                        sueños</span>.
                                </h2>
                                <div class="w-20 h-1 bg-[#1a1d56] rounded-full mt-4"></div>
                            </div>

                            <!-- Cuerpo del Mensaje -->
                            <div class="text-neutral-600 text-base md:text-lg leading-relaxed space-y-5 text-justify">
                                <p>
                                    Su misión es planear, organizar, dirigir y controlar en conjunto con las áreas
                                    involucradas las <strong class="text-[#1a1d56] font-bold">actividades culturales,
                                        artísticas y deportivas</strong>, fomentando la sana competencia, la camaradería
                                    y el mejoramiento académico de todos los miembros de la comunidad escolar.
                                </p>

                                <p>
                                    El <strong class="text-neutral-800">Programa Anual de Actividades</strong> y el
                                    <strong class="text-neutral-800">Calendario Escolar</strong>, coordinados con la
                                    Comunidad Educativa, apoyan la educación integral basada en los valores
                                    fundamentales del Colegio Andino.
                                </p>

                                <!-- Cita destacada -->
                                <div class="relative pl-6 md:pl-8 py-2 my-6">
                                    <div
                                        class="absolute left-0 top-0 bottom-0 w-[3px] bg-gradient-to-b from-[#ae0c26] to-[#1a1d56] rounded-full">
                                    </div>
                                    <blockquote class="italic text-neutral-700 font-medium text-lg leading-snug">
                                        "Se promueve el desarrollo de Talleres Deportivos y Culturales para fortalecer
                                        el desarrollo físico y la recreación cultural, contribuyendo a la formación
                                        integral y orientando los talentos e intereses de nuestros estudiantes."
                                    </blockquote>
                                </div>
                            </div>

                        </div>
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