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
    <Head :title="title || 'Historia - Colegio Andino'" />

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
                    Nuestra Trayectoria
                </span>
                <h1 class="font-display text-4xl md:text-6xl font-black text-[#1a1d56] tracking-tight mb-4">
                    Historia
                </h1>
                <nav aria-label="Breadcrumb" class="flex justify-center items-center gap-2 text-sm font-semibold font-sans">
                    <Link href="/"
                        class="text-[#ae0c26] hover:underline transition-all focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#ae0c26] rounded-sm">
                        Inicio
                    </Link>
                    <span class="text-neutral-400">/</span>
                    <span class="text-neutral-400">¿Quiénes somos?</span>
                    <span class="text-neutral-400">/</span>
                    <span class="text-neutral-700">Historia</span>
                </nav>
            </div>
        </section>

        <!-- Sección Contenido Historia -->
        <section ref="sectionRef" class="py-16 md:py-24 bg-white font-sans">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 md:gap-16 items-center">
                    
                    <!-- Imagen Histórica a la izquierda -->
                    <div class="lg:col-span-6 lg:sticky lg:top-24" data-reveal>
                        <div class="relative group">
                            <!-- Marco / sombra trasera decorativa -->
                            <div
                                class="absolute -inset-2 bg-gradient-to-br from-[#1a1d56] to-[#ae0c26] rounded-3xl opacity-15 blur-lg">
                            </div>
                            <img class="relative w-full h-auto object-cover rounded-2xl shadow-xl border border-neutral-100"
                                src="/img/quienes/historia.png"
                                alt="Historia del Colegio Andino" />

                            <!-- Badge conmemorativo con el año de traslado a Huancayo -->
                            <div
                                class="absolute -bottom-6 -right-6 w-24 h-24 md:w-28 md:h-28 rounded-full bg-white shadow-xl border-2 border-[#1a1d56] flex flex-col items-center justify-center rotate-[-4deg]">
                                <div class="absolute inset-1.5 rounded-full border border-dashed border-[#ae0c26]/40"></div>
                                <span class="text-[9px] md:text-[10px] font-bold uppercase tracking-widest text-[#ae0c26] mb-0.5">Llegada a</span>
                                <span class="font-display font-black text-lg md:text-xl text-[#1a1d56] leading-none">Huancayo</span>
                                <span class="text-[9px] md:text-[10px] font-bold uppercase tracking-widest text-neutral-500 mt-0.5">1913</span>
                            </div>
                        </div>
                    </div>

                    <!-- Contenido Editorial a la derecha -->
                    <div class="lg:col-span-6" data-reveal>
                        <div class="space-y-6">
                            
                            <!-- Antetítulo, Título y Línea Divisora -->
                            <div>
                                <span class="text-xs font-black uppercase tracking-[0.2em] text-[#ae0c26] block mb-2">
                                    Legado Institucional
                                </span>
                                <h2 class="font-display text-4xl md:text-6xl font-black text-[#1a1d56] leading-tight">
                                    Desde <span class="text-[#ae0c26]">1913</span>
                                </h2>
                                <div class="w-24 h-1 bg-[#1a1d56] rounded-full mt-4"></div>
                            </div>

                            <!-- Cuerpo del Texto Histórico -->
                            <div class="text-neutral-600 text-base md:text-lg leading-relaxed space-y-5 text-justify">
                                <p>
                                    El <strong class="text-[#1a1d56] font-bold">Colegio Andino</strong> fue fundado en Tarma en <strong class="text-[#ae0c26] font-bold">1907</strong> por el Rev. Vance. En 1913, bajo la dirección del señor Clarence Snell, se planificó su traslado a la ciudad de Huancayo. El viaje fue arduo y se realizó utilizando animales de carga, logrando arribar a Huancayo el <strong class="text-neutral-800">30 de septiembre</strong> de ese mismo año.
                                </p>

                                <p>
                                    Inicialmente, el colegio ofrecía instrucción primaria y posteriormente se expandió para incluir educación secundaria y comercial, consolidándose como una de las instituciones educativas líderes en la región central del Perú. En 1939, debido a una reorganización, se cerró la sección secundaria. La evolución del alumnado refleja su impacto: 40 estudiantes en Tarma, 100 en Huancayo para 1919, 358 en 1928, manteniendo un promedio constante de 350 alumnos entre 1930 y 1954. Hoy en día, la institución alberga a <strong class="text-[#1a1d56]">más de 800 estudiantes</strong>.
                                </p>

                                <!-- Cita / Resumen conmemorativo -->
                                <div class="relative pl-6 md:pl-8 py-2 my-6">
                                    <div class="absolute left-0 top-0 bottom-0 w-[3px] bg-gradient-to-b from-[#1a1d56] to-[#ae0c26] rounded-full"></div>
                                    <blockquote class="italic text-neutral-700 font-medium text-lg leading-snug">
                                        "Con más de 110 años de vida institucional, continuamos promoviendo una educación de excelencia, fe y valores para el desarrollo de la región."
                                    </blockquote>
                                </div>

                                <p>
                                    Actualmente, bajo la dirección de la <strong class="text-[#1a1d56]">Mg. María Isabel Mendoza Solis</strong>, la institución lidera un renovado plan de acción pedagógico e infraestructura para seguir formando líderes íntegros al servicio de la comunidad.
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