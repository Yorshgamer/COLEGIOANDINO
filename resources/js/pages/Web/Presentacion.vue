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
    <Head :title="title || 'Presentación - Colegio Andino'" />

    <WebLayout>
        <!-- Header con Breadcrumb -->
        <section
            class="relative bg-gradient-to-b from-neutral-100 to-white py-14 md:py-20 border-b border-neutral-200/60 overflow-hidden">
            <!-- Regla decorativa sutil -->
            <div
                class="absolute top-0 left-1/2 -translate-x-1/2 w-24 h-1 bg-gradient-to-r from-[#ae0c26] to-[#1a1d56] rounded-full">
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <span class="text-xs font-bold uppercase tracking-[0.25em] text-[#ae0c26]/80 block mb-3 font-sans">
                    Carta Institucional
                </span>
                <h1 class="font-display text-4xl md:text-6xl font-black text-[#1a1d56] tracking-tight mb-4">
                    Presentación
                </h1>
                <nav aria-label="Breadcrumb" class="flex justify-center items-center gap-2 text-sm font-semibold font-sans">
                    <Link href="/"
                        class="text-[#ae0c26] hover:underline transition-all focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#ae0c26] rounded-sm">
                        Inicio
                    </Link>
                    <span class="text-neutral-400">/</span>
                    <span class="text-neutral-400">¿Quiénes somos?</span>
                    <span class="text-neutral-400">/</span>
                    <span class="text-neutral-700">Presentación</span>
                </nav>
            </div>
        </section>

        <!-- Sección de Contenido Principal -->
        <section ref="sectionRef" class="py-16 md:py-24 bg-white font-sans">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 md:gap-16 items-start">

                    <!-- Imagen de la Directora con Sello Institucional -->
                    <div class="lg:col-span-5 lg:sticky lg:top-24" data-reveal>
                        <div class="relative">
                            <!-- Marco / sombra trasera decorativa -->
                            <div
                                class="absolute -inset-2 bg-gradient-to-br from-[#ae0c26] to-[#1a1d56] rounded-3xl opacity-15 blur-lg">
                            </div>
                            <img class="relative w-full h-auto object-cover rounded-2xl shadow-xl border border-neutral-100"
                                src="/img/quienes/directora.png"
                                alt="Directora Colegio Andino - Mg. María Isabel Mendoza Solis" />

                            <!-- Sello conmemorativo 110 años -->
                            <div
                                class="absolute -bottom-6 -right-6 w-24 h-24 md:w-28 md:h-28 rounded-full bg-white shadow-xl border-2 border-[#ae0c26] flex flex-col items-center justify-center rotate-[-6deg]">
                                <div class="absolute inset-1.5 rounded-full border border-dashed border-[#1a1d56]/40"></div>
                                <span class="font-display font-black text-2xl md:text-3xl text-[#ae0c26] leading-none">110</span>
                                <span class="text-[9px] md:text-[10px] font-bold uppercase tracking-widest text-[#1a1d56] mt-0.5">Años</span>
                            </div>
                        </div>
                    </div>

                    <!-- Editorial / Mensaje de la Directora -->
                    <div class="lg:col-span-7" data-reveal>
                        <div class="space-y-7">
                            <!-- Antetítulo y Título -->
                            <div>
                                <span class="text-xs font-black uppercase tracking-[0.2em] text-[#ae0c26] block mb-3">
                                    Mensaje de Dirección
                                </span>
                                <h2 class="font-display text-4xl md:text-5xl font-black text-[#1a1d56] leading-[1.05]">
                                    Bienvenido al
                                    <span class="block italic text-[#ae0c26]">Colegio Andino</span>
                                </h2>
                            </div>

                            <!-- Cuerpo del Mensaje -->
                            <div class="text-neutral-600 text-base md:text-lg leading-relaxed space-y-5">
                                <p class="relative font-display font-bold text-[#1a1d56] text-xl md:text-2xl pl-2">
                                    <span class="absolute -left-1 -top-3 text-5xl text-[#ae0c26]/25 font-display select-none" aria-hidden="true">&ldquo;</span>
                                    Estimada comunidad educativa:
                                </p>

                                <p class="text-justify">
                                    Me es muy grato dar la bienvenida a la presente página web del Colegio Particular
                                    Andino, institución educativa, cuya promotoría es la Iglesia Metodista del Perú.
                                </p>

                                <!-- Cita Destacada -->
                                <div class="relative pl-6 md:pl-8 py-2 my-7">
                                    <div class="absolute left-0 top-0 bottom-0 w-[3px] bg-gradient-to-b from-[#ae0c26] to-[#1a1d56] rounded-full"></div>
                                    <span class="absolute -left-1 -top-4 text-6xl font-display text-[#ae0c26]/15 select-none" aria-hidden="true">&ldquo;</span>
                                    <blockquote class="relative italic text-neutral-700 font-medium text-lg leading-snug">
                                        A través de esta página web, queremos que nuestra comunidad educativa pueda
                                        acceder a información relevante y significativa de nuestro quehacer escolar.
                                    </blockquote>
                                </div>

                                <p class="text-justify">
                                    Quedan todos invitados a participar de este espacio virtual que nos convoca como
                                    comunidad y que nos identifica como parte de la Familia Andino, además nos presenta
                                    una nueva oportunidad para crecer y soñar, lo cual lo hacemos con la alegría y la
                                    esperanza de poder lograr los objetivos que nos propongamos.
                                </p>

                                <p class="text-justify">
                                    Al cumplir más de <strong class="text-[#ae0c26] font-bold">110 años</strong> de
                                    vida institucional, reconocemos las dimensiones que orientan nuestra tarea
                                    pedagógica: <strong class="text-neutral-800">Amor, Humanidad y Cultura</strong>,
                                    como también el lema que acompaña a nuestra familia:
                                    <span class="block mt-4 font-display text-xl font-bold text-neutral-900 italic">
                                        "Conoceréis la verdad, y la verdad os hará libres"
                                        <small class="block mt-1 text-neutral-500 font-sans font-normal not-italic text-sm tracking-wide">Juan 8:32</small>
                                    </span>
                                </p>
                            </div>

                            <!-- Firma -->
                            <div class="relative pt-8">
                                <div class="absolute top-0 left-0 w-16 h-[3px] bg-[#ae0c26] rounded-full"></div>
                                <h3 class="font-display text-2xl font-extrabold text-[#1a1d56] mt-4">
                                    Mg. María Isabel Mendoza Solis
                                </h3>
                                <span class="text-xs font-black uppercase tracking-wider text-[#ae0c26]">
                                    Directora General
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </WebLayout>
</template>

<style scoped>
/* Redefinimos las clases utilitarias para apuntar siempre a la fuente Gilroy */
.font-display,
.font-sans {
    font-family: 'Gilroy', ui-sans-serif, system-ui, sans-serif;
}

/* Animaciones suaves al scroll */
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