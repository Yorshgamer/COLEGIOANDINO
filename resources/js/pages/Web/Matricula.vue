<script setup lang="ts">
import WebLayout from '@/layouts/web/WebLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

defineProps<{
    title?: string;
}>();

const sectionRef = ref<HTMLElement | null>(null);

const documentos = [
    {
        titulo: 'Propuesta Educativa 2026',
        imagen: '/img/matricula/propuesta.png',
        url: 'https://drive.google.com/file/d/1avjKj09zCMytrCQWOKFAKzx2P0RZv3wN/view?usp=sharing',
    },
    {
        titulo: 'Reglamento Interno 2026',
        imagen: '/img/matricula/reglamento.png',
        url: 'https://drive.google.com/file/d/12LHn7ICRMHfWbXe-Zq9EncJmgTrIr24B/view?usp=sharing',
    },
    {
        titulo: 'Contrato de Matrícula',
        imagen: '/img/matricula/contrato.png',
        url: 'https://drive.google.com/file/d/179yDMUyXAPx15oC2kgJPM_kFz6aFShIw/view?usp=sharing',
    },
    {
        titulo: 'Cargo de Recepción',
        imagen: '/img/matricula/cargo.png',
        url: 'https://drive.google.com/file/d/18PDortCyU16yw_8elps1zm9EIQ19tA3t/view?usp=sharing',
    },
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

    <Head :title="title || 'Matrícula 2026 - Colegio Andino'" />

    <WebLayout>
        <!-- Hero / Banner Principal Matrícula -->
        <section class="relative min-h-[500px] md:min-h-[580px] flex items-center bg-[#1a1d56] overflow-hidden">
            <!-- Barra superior roja -->
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-[#ae0c26] z-30"></div>

            <!-- Fondo de imagen con Overlay azul oscuro parejo -->
            <div class="absolute inset-0 z-0">
                <img src="/img/matricula/fondo.jpg" alt="Fondo Matrícula Colegio Andino"
                    class="w-full h-full object-cover object-center" />
                <div class="absolute inset-0 bg-[#1a1d56]/55"></div>
            </div>

            <!-- Contenido del Banner -->
            <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full py-10 md:py-16">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">

                    <!-- Lado Izquierdo: Título gráfico a la izquierda (intacto) -->
                    <div class="lg:col-span-7 flex justify-start text-left">
                        <img src="/img/matricula/texto.png" alt="Matrícula 2026"
                            class="w-full max-w-lg sm:max-w-xl md:max-w-2xl h-auto object-contain drop-shadow-2xl" />
                    </div>

                    <!-- Lado Derecho: Columna libre para reservar espacio en el grid -->
                    <div class="lg:col-span-5 hidden lg:block"></div>

                </div>
            </div>

            <!-- Imagen de la Niña Estudiante pegada directamente a la parte inferior -->
            <div
                class="absolute bottom-0 right-4 sm:right-8 lg:right-16 z-20 flex justify-end items-end pointer-events-none">
                <img src="/img/matricula/nina.png" alt="Estudiante Colegio Andino"
                    class="w-auto h-auto max-h-[380px] sm:max-h-[460px] lg:max-h-[560px] object-contain object-bottom drop-shadow-2xl block -mb-1" />
            </div>
        </section>

        <div ref="sectionRef">
            <!-- Sección Información Matrícula -->
            <section class="py-16 md:py-24 bg-white font-sans">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

                        <!-- Texto Informativo -->
                        <div class="lg:col-span-6" data-reveal>
                            <span class="text-xs font-black uppercase tracking-[0.2em] text-[#ae0c26] block mb-2">
                                Proceso de Inscripción
                            </span>
                            <h2 class="font-display text-3xl md:text-5xl font-black text-[#1a1d56] leading-[1.15] mb-6">
                                ¡Iniciamos una <br />
                                <span class="italic text-[#ae0c26]">nueva etapa</span>!
                            </h2>
                            <div class="w-20 h-1 bg-[#1a1d56] rounded-full mb-6"></div>

                            <div class="text-neutral-600 text-base md:text-lg leading-relaxed space-y-5 text-justify">
                                <p>
                                    Estimados padres de familia, ya se encuentra disponible el proceso de <strong
                                        class="text-[#1a1d56] font-bold">Matrícula 2026</strong> para el próximo año
                                    escolar.
                                </p>
                                <p>
                                    Para completar el registro de sus hijos, es indispensable realizar la lectura de
                                    nuestros documentos institucionales, completar la documentación requerida y subirla
                                    a nuestra plataforma <strong class="text-neutral-800 font-bold">Sieweb</strong>.
                                    Este proceso digital nos permite asegurar su vacante de manera rápida y organizada,
                                    garantizando que todo esté listo para recibir a nuestros estudiantes.
                                </p>
                            </div>
                        </div>

                        <!-- Imagen Ilustrativa -->
                        <div class="lg:col-span-6" data-reveal>
                            <div
                                class="relative rounded-3xl overflow-hidden shadow-xl border border-neutral-100 group aspect-[4/3] md:aspect-[5/4]">
                                <img src="/img/matricula/ensenando.jpg" alt="Estudiantes aprendiendo"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            <!-- Sección Descargar Documentos -->
            <section class="py-16 md:py-24 bg-neutral-50/70 font-sans border-t border-neutral-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                    <div class="text-center mb-14" data-reveal>
                        <span class="text-xs font-black uppercase tracking-[0.2em] text-[#ae0c26] block mb-2">
                            Documentación Requerida
                        </span>
                        <h2 class="font-display text-3xl md:text-5xl font-black text-[#1a1d56]">
                            Descargar Documentos
                        </h2>
                        <div class="w-20 h-1 bg-[#1a1d56] mx-auto rounded-full mt-4"></div>
                    </div>

                    <!-- Grid de Documentos -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
                        <div v-for="(doc, idx) in documentos" :key="idx" data-reveal>
                            <a :href="doc.url" target="_blank" rel="noopener noreferrer"
                                class="group block h-full bg-white rounded-2xl p-6 shadow-sm hover:shadow-xl border border-neutral-100 hover:border-neutral-200 transition-all duration-300 hover:-translate-y-1.5 flex flex-col items-center justify-center text-center min-h-[280px]">

                                <div class="w-auto h-36 md:h-40 mb-4 flex items-center justify-center p-2">
                                    <img :src="doc.imagen" :alt="doc.titulo"
                                        class="max-h-full max-w-full object-contain group-hover:scale-105 transition-transform duration-300 drop-shadow-xs" />
                                </div>

                                <span
                                    class="font-display font-bold text-[#1a1d56] text-base group-hover:text-[#ae0c26] transition-colors">
                                    {{ doc.titulo }}
                                </span>
                            </a>
                        </div>
                    </div>

                </div>
            </section>

            <!-- Sección Video Tutorial SIEWEB -->
            <section class="py-16 md:py-24 bg-white font-sans">
                <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center" data-reveal>
                    <span class="text-xs font-black uppercase tracking-[0.2em] text-[#ae0c26] block mb-2">
                        Guía Paso a Paso
                    </span>

                    <h2
                        class="font-display text-3xl md:text-5xl font-black text-[#1a1d56] mb-10 flex flex-wrap items-center justify-center gap-3">
                        <span>Video Tutorial Matrícula</span>
                        <span class="text-[#ae0c26]">&gt;</span>
                        <span class="px-4 py-1.5 bg-[#ae0c26] text-white rounded-xl shadow-xs">SIEWEB</span>
                    </h2>

                    <!-- Reproductor de Vimeo -->
                    <div
                        class="relative aspect-video w-full rounded-2xl overflow-hidden shadow-2xl bg-black border border-neutral-100">
                        <iframe
                            src="https://player.vimeo.com/video/1155827275?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479"
                            class="absolute inset-0 w-full h-full" frameborder="0"
                            allow="autoplay; fullscreen; picture-in-picture; clipboard-write; encrypted-media; web-share"
                            referrerpolicy="strict-origin-when-cross-origin" title="TUTORIAL SIEWEB">
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