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
    <Head :title="title || 'Capellanía - Colegio Andino'" />

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
                    Formación Espiritual
                </span>
                <h1 class="font-display text-4xl md:text-6xl font-black text-[#1a1d56] tracking-tight mb-4">
                    Capellanía
                </h1>
                <nav aria-label="Breadcrumb" class="flex justify-center items-center gap-2 text-sm font-semibold font-sans">
                    <Link href="/"
                        class="text-[#ae0c26] hover:underline transition-all focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#ae0c26] rounded-sm">
                        Inicio
                    </Link>
                    <span class="text-neutral-400">/</span>
                     <span class="text-neutral-400">Orientación</span>
                    <span class="text-neutral-400">/</span>
                    <span class="text-neutral-700">Capellanía</span>
                </nav>
            </div>
        </section>

        <!-- Sección de Contenido Principal -->
        <section ref="sectionRef" class="py-16 md:py-24 bg-white font-sans">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 md:gap-16 items-center">
                    
                    <!-- Imagen/Escudo a la izquierda -->
                    <div class="lg:col-span-5 lg:sticky lg:top-24" data-reveal>
                        <div class="relative w-full flex justify-center">
                            <!-- Glow circular de fondo -->
                            <div class="absolute w-64 h-64 md:w-80 md:h-80 rounded-full bg-[#ae0c26]/10 -z-10 blur-2xl"></div>
                            
                            <img class="w-full h-auto max-h-96 md:max-h-[620px] object-contain drop-shadow-lg"
                                src="/img/propuesta/capellania.png"
                                alt="Capellanía - Escudo con Cruz" />
                        </div>
                    </div>

                    <!-- Texto a la derecha -->
                    <div class="lg:col-span-7" data-reveal>
                        <div class="space-y-6">
                            
                            <!-- Antetítulo y Título -->
                            <div>
                                <span class="text-xs font-black uppercase tracking-[0.2em] text-[#ae0c26] block mb-2">
                                    Servicio Pastoral
                                </span>
                                <h2 class="font-display text-4xl md:text-5xl font-black text-[#1a1d56] leading-[1.1]">
                                    Presencia, fe y <br />
                                    <span class="italic text-[#ae0c26]">orientación</span>
                                </h2>
                                <div class="w-20 h-1 bg-[#1a1d56] rounded-full mt-4"></div>
                            </div>

                            <!-- Cuerpo del Mensaje -->
                            <div class="text-neutral-600 text-base md:text-lg leading-relaxed space-y-5 text-justify">
                                <p>
                                    La <strong class="text-[#1a1d56] font-bold">Capellanía</strong> es un servicio escolar, cuyo objetivo es promover una formación integral de los miembros de la Comunidad Educativa, compartiendo principios y valores éticos cristianos según las enseñanzas de Jesucristo, para crear un mundo más humano.
                                </p>

                                <p>
                                    La Capellanía busca contribuir a la formación de niños y jóvenes de la <strong class="text-neutral-800">Familia Andino</strong>, forjando hombres y mujeres con firmes principios cristianos y el sello distintivo de su comunidad educativa que promueve los valores tradicionales de <strong class="text-[#ae0c26]">Cultura, Amor y Humanidad</strong>.
                                </p>

                                <!-- Cita destacada -->
                                <div class="relative pl-6 md:pl-8 py-2 my-6">
                                    <div class="absolute left-0 top-0 bottom-0 w-[3px] bg-gradient-to-b from-[#ae0c26] to-[#1a1d56] rounded-full"></div>
                                    <blockquote class="italic text-neutral-700 font-medium text-lg leading-snug">
                                        "Creemos que educar también significa transmitir esperanza, y para hacerlo, hay que creerla, disfrutarla y, sobre todo, ponerla en práctica en las acciones cotidianas."
                                    </blockquote>
                                </div>

                                <p>
                                    Además de su función pastoral, la Capellanía apoya al Área de Educación Cristiana, participando en las acciones solidarias y académicas que se realizan en los diferentes niveles educativos.
                                </p>

                                <p class="text-neutral-800 font-medium">
                                    Los Capellanes que sirven en las instituciones educativas Metodistas son pastores nombrados oficialmente por la <strong class="text-[#1a1d56]">Iglesia Metodista del Perú</strong>.
                                </p>
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