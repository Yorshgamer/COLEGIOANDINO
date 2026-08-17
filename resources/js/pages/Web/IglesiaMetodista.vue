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
    <Head :title="title || 'Iglesia Metodista del Perú - Colegio Andino'" />

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
                    Nuestra Promotoría
                </span>
                <h1 class="font-display text-4xl md:text-6xl font-black text-[#1a1d56] tracking-tight mb-4">
                    Iglesia Metodista del Perú
                </h1>
                <nav aria-label="Breadcrumb" class="flex justify-center items-center gap-2 text-sm font-semibold font-sans">
                    <Link href="/"
                        class="text-[#ae0c26] hover:underline transition-all focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#ae0c26] rounded-sm">
                        Inicio
                    </Link>
                    <span class="text-neutral-400">/</span>
                    <span class="text-neutral-400">¿Quiénes somos?</span>
                    <span class="text-neutral-400">/</span>
                    <span class="text-neutral-700">Iglesia Metodista</span>
                </nav>
            </div>
        </section>

        <!-- Sección Contenido Principal -->
        <section ref="sectionRef" class="py-16 md:py-24 bg-white font-sans">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 md:gap-16 items-start">
                    
                    <!-- Imagen a la izquierda (Niña con libro) + Badge Año Fundacional -->
                    <div class="lg:col-span-5 lg:sticky lg:top-24" data-reveal>
                        <div class="relative">
                            <!-- Marco / sombra trasera decorativa -->
                            <div
                                class="absolute -inset-2 bg-gradient-to-br from-[#1a1d56] to-[#ae0c26] rounded-3xl opacity-15 blur-lg">
                            </div>
                            <img 
                                class="relative w-full h-auto object-cover rounded-2xl shadow-xl border border-neutral-100" 
                                src="/img/quienes/niña-libro.png" 
                                alt="Estudiante leyendo" 
                            />

                            <!-- Badge conmemorativo 1877 en Perú -->
                            <div
                                class="absolute -bottom-6 -right-6 w-24 h-24 md:w-28 md:h-28 rounded-full bg-white shadow-xl border-2 border-[#1a1d56] flex flex-col items-center justify-center rotate-[4deg]">
                                <div class="absolute inset-1.5 rounded-full border border-dashed border-[#ae0c26]/40"></div>
                                <span class="text-[9px] md:text-[10px] font-bold uppercase tracking-widest text-[#ae0c26] mb-0.5">Desde</span>
                                <span class="font-display font-black text-xl md:text-2xl text-[#1a1d56] leading-none">1877</span>
                                <span class="text-[8px] md:text-[9px] font-bold uppercase tracking-widest text-neutral-500 mt-0.5">En Perú</span>
                            </div>
                        </div>
                    </div>

                    <!-- Editorial / Texto a la derecha -->
                    <div class="lg:col-span-7" data-reveal>
                        <div class="space-y-7">
                            
                            <!-- Antetítulo y Título -->
                            <div>
                                <span class="text-xs font-black uppercase tracking-[0.2em] text-[#ae0c26] block mb-3">
                                    Historia y Misión
                                </span>
                                <h2 class="font-display text-3xl md:text-5xl font-black text-[#1a1d56] leading-[1.1]">
                                    Educación Cristiana con
                                    <span class="block italic text-[#ae0c26]">Vocación de Servicio</span>
                                </h2>
                            </div>

                            <!-- Cuerpo del Texto -->
                            <div class="text-neutral-600 text-base md:text-lg leading-relaxed space-y-5">
                                <p class="text-justify">
                                    La <strong class="text-[#1a1d56] font-bold">Iglesia Metodista del Perú</strong> es la entidad promotora que auspicia al Colegio Particular Andino. Las raíces de esta institución religiosa se remontan al movimiento Metodista de la Inglaterra del siglo XVIII, impulsado por su fundador, el <strong class="text-neutral-800">Rev. John Wesley</strong>.
                                </p>

                                <p class="text-justify">
                                    Establecida en el Perú en <strong class="text-[#ae0c26] font-bold">1877</strong>, la Iglesia Metodista tiene la misión de anunciar el Reino de Dios, proclamando el evangelio de Jesucristo, sirviendo al prójimo y cumpliendo un rol profético en el contexto de nuestra realidad y cultura. Desde su llegada, el principal esfuerzo de los primeros misioneros se concentró en la educación, demostrando una extraordinaria fe en su poder transformador.
                                </p>

                                <!-- Cita / Destacado institucional -->
                                <div class="relative pl-6 md:pl-8 py-2 my-7">
                                    <div class="absolute left-0 top-0 bottom-0 w-[3px] bg-gradient-to-b from-[#1a1d56] to-[#ae0c26] rounded-full"></div>
                                    <span class="absolute -left-1 -top-4 text-6xl font-display text-[#1a1d56]/15 select-none" aria-hidden="true">&ldquo;</span>
                                    <blockquote class="relative italic text-neutral-700 font-medium text-lg leading-snug">
                                        Desde la perspectiva Metodista, la educación sirve como un vehículo fundamental para la transformación social e individual.
                                    </blockquote>
                                </div>

                                <p class="text-justify">
                                    El mejor aporte de los colegios metodistas en el país es impartir una enseñanza basada en <strong class="text-neutral-800">principios y valores cristianos</strong>, promoviendo la inclusión e igualdad sin distinción de géneros, razas, credos o culturas.
                                </p>

                                <p class="text-justify">
                                    El fin supremo de nuestra propuesta educativa es mejorar la calidad de vida de los alumnos y formarlos como instrumentos de cambio en nuestra sociedad a través del servicio solidario, reflejando el gran amor de Dios en sus vidas.
                                </p>
                            </div>

                            <!-- Tarjeta Informativa Resumen -->
                            <div class="pt-4">
                                <div class="p-6 bg-neutral-50 rounded-2xl border border-neutral-100/80 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                    <div>
                                        <h4 class="font-display font-extrabold text-[#1a1d56] text-lg">
                                            Pilares Institucionales
                                        </h4>
                                        <p class="text-sm text-neutral-500 mt-0.5">
                                            Valores que guían nuestro modelo pedagógico
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="px-3 py-1 bg-white text-[#ae0c26] text-xs font-black rounded-lg border border-neutral-200/80 shadow-xs">
                                            AMOR
                                        </span>
                                        <span class="px-3 py-1 bg-white text-[#1a1d56] text-xs font-black rounded-lg border border-neutral-200/80 shadow-xs">
                                            HUMANIDAD
                                        </span>
                                        <span class="px-3 py-1 bg-white text-neutral-700 text-xs font-black rounded-lg border border-neutral-200/80 shadow-xs">
                                            CULTURA
                                        </span>
                                    </div>
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

/* Animación de entrada reveal */
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