<script setup lang="ts">
import WebLayout from '@/layouts/web/WebLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import { Mail, Award, Users, HeartHandshake } from '@lucide/vue';

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
    <Head :title="title || 'Trabaja con Nosotros - Colegio Andino'" />

    <WebLayout>
        <!-- Hero / Banner Principal -->
        <section class="relative min-h-[480px] md:min-h-[560px] flex items-center bg-[#1a1d56] overflow-hidden">
            <!-- Barra superior roja -->
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-[#ae0c26] z-30"></div>

            <!-- Fondo con Overlay -->
            <div class="absolute inset-0 z-0">
                <img src="/img/nosotros/seccion.jpg" alt="Trabaja con Nosotros - Colegio Andino" class="w-full h-full object-cover object-center" />
                <div class="absolute inset-0 bg-[#1a1d56]/75"></div>
            </div>

            <!-- Contenido del Banner -->
            <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center py-20 w-full">
                <h1 class="font-display text-4xl sm:text-6xl lg:text-7xl font-black text-white leading-tight drop-shadow-lg uppercase">
                    TRABAJA CON<br />NOSOTROS
                </h1>
                <div class="w-24 h-1.5 bg-[#ae0c26] mx-auto rounded-full mt-6"></div>
            </div>
        </section>

        <div ref="sectionRef" class="font-sans">
            <!-- Sección: Forma parte de la Familia Andino -->
            <section class="py-16 md:py-24 bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    
                    <!-- Título -->
                    <div class="text-center mb-12 md:mb-16" data-reveal>
                        <span class="text-xs font-black uppercase tracking-[0.2em] text-[#ae0c26] block mb-2">
                            Oportunidades Laborales
                        </span>
                        <h2 class="font-display text-3xl sm:text-5xl font-black text-[#1a1d56]">
                            Forma parte de la Familia Andino
                        </h2>
                        <div class="w-20 h-1 bg-[#1a1d56] mx-auto rounded-full mt-4"></div>
                    </div>

                    <!-- Contenido a 2 columnas (Imagen + Texto) -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-center">
                        <!-- Imagen Izquierda -->
                        <div class="lg:col-span-6" data-reveal>
                            <div class="relative rounded-3xl overflow-hidden shadow-2xl border border-neutral-100 aspect-[4/3] lg:aspect-auto lg:h-[500px]">
                                <img src="/img/nosotros/DSC05599 (1).jpg" alt="Comunidad Colegio Andino" class="w-full h-full object-cover" />
                            </div>
                        </div>

                        <!-- Texto Derecha -->
                        <div class="lg:col-span-6" data-reveal>
                            <div class="bg-neutral-50 border border-neutral-100 rounded-3xl p-8 sm:p-10 shadow-sm space-y-6">
                                <p class="text-neutral-600 text-base sm:text-lg leading-relaxed text-justify">
                                    Buscamos profesionales apasionados y comprometidos con la excelencia educativa que deseen
                                    trascender a través de la formación de nuevas generaciones. Al integrarte a nuestro equipo,
                                    te unes a una institución con más de un siglo de trayectoria, donde valoramos la innovación, la
                                    vocación de servicio y los principios cristianos.
                                </p>
                                <p class="text-neutral-600 text-base sm:text-lg leading-relaxed text-justify">
                                    Aquí encontrarás un entorno que promueve el crecimiento profesional y humano, fundamentado en nuestros pilares de <strong>Amor, Humanidad y Cultura</strong>. Si estás listo para potenciar habilidades y dejar una huella positiva en la comunidad, este es el lugar para desarrollar tu talento y vocación.
                                </p>

                                <!-- Callout de contacto para CV -->
                                <div class="pt-4 border-t border-neutral-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-[#ae0c26]/10 text-[#ae0c26] flex items-center justify-center shrink-0">
                                            <Mail class="size-5" />
                                        </div>
                                        <div>
                                            <span class="text-xs font-bold uppercase tracking-wider text-neutral-400 block">Envía tu CV a:</span>
                                            <a href="mailto:secretaria@andino.edu.pe" class="text-sm sm:text-base font-bold text-[#1a1d56] hover:text-[#ae0c26] transition-colors">
                                                secretaria@andino.edu.pe
                                            </a>
                                        </div>
                                    </div>
                                    <Link href="/contacto"
                                        class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-[#1a1d56] hover:bg-[#ae0c26] text-white font-bold text-xs uppercase tracking-wider transition-all duration-300 shadow-md shrink-0">
                                        Contáctanos
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Sección: Prácticas Inclusivas -->
            <section class="py-16 md:py-24 bg-neutral-50/70 border-t border-neutral-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    
                    <!-- Badge Flotante Superior -->
                    <div class="text-center mb-8" data-reveal>
                        <div class="inline-block bg-[#ae0c26] text-white px-8 py-3.5 rounded-full shadow-lg">
                            <p class="font-display font-bold text-lg sm:text-2xl m-0">
                                Postula y forma parte de nuestra historia
                            </p>
                        </div>
                    </div>

                    <!-- Título -->
                    <div class="text-center mb-8" data-reveal>
                        <h2 class="font-display text-3xl sm:text-5xl font-black text-[#1a1d56] max-w-4xl mx-auto leading-tight">
                            Distinguidos por nuestras prácticas inclusivas
                        </h2>
                    </div>

                    <!-- Párrafo descriptivo centrado -->
                    <div class="max-w-4xl mx-auto mb-14" data-reveal>
                        <p class="text-neutral-600 text-base sm:text-lg leading-relaxed text-justify sm:text-center">
                            Somos una institución que piensa en todos. Reconocidos por el Ministerio de Trabajo como una de las <strong>Empresas Inclusivas</strong> del país, reafirmamos nuestro compromiso con la diversidad y la equidad. En la Familia Andino, trabajamos para que cada persona encuentre un espacio de respeto, valor y oportunidades sin excepciones.
                        </p>
                    </div>

                    <!-- Grid de 3 Fotos -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8" data-reveal>
                        <div class="rounded-2xl overflow-hidden shadow-lg border border-neutral-200/80 group aspect-[4/3]">
                            <img src="/img/nosotros/DSC03543.JPG" alt="Prácticas Inclusivas Colegio Andino" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                        </div>
                        <div class="rounded-2xl overflow-hidden shadow-lg border border-neutral-200/80 group aspect-[4/3]">
                            <img src="/img/nosotros/DSC03664 (1).jpg" alt="Prácticas Inclusivas Colegio Andino" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                        </div>
                        <div class="rounded-2xl overflow-hidden shadow-lg border border-neutral-200/80 group aspect-[4/3]">
                            <img src="/img/nosotros/DSC03543.JPG" alt="Prácticas Inclusivas Colegio Andino" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                        </div>
                    </div>

                </div>
            </section>
        </div>
    </WebLayout>
</template>

<style scoped>
.font-display,
.font-sans {
    font-family: 'Gilroy', ui-sans-serif, system-ui, sans-serif;
}

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