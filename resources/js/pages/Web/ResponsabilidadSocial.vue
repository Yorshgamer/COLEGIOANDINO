<script setup lang="ts">
import WebLayout from '@/layouts/web/WebLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import { Heart, Sparkles, Award } from '@lucide/vue';

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
    <Head :title="title || 'Responsabilidad Social - Colegio Andino'" />

    <WebLayout>
        <!-- Hero / Banner Principal -->
        <section class="relative min-h-[460px] md:min-h-[540px] flex items-center bg-[#1a1d56] overflow-hidden">
            <!-- Barra superior roja -->
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-[#ae0c26] z-30"></div>

            <!-- Fondo con Overlay -->
            <div class="absolute inset-0 z-0">
                <img src="/img/nosotros/seccion.jpg" alt="Responsabilidad Social - Colegio Andino" class="w-full h-full object-cover object-center" />
                <div class="absolute inset-0 bg-[#1a1d56]/75"></div>
            </div>

            <!-- Contenido del Banner -->
            <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center py-20 w-full">
                <span class="text-xs font-black uppercase tracking-[0.25em] text-[#ae0c26] bg-white/90 px-4 py-1.5 rounded-full inline-block mb-4 shadow-md font-sans">
                    Compromiso Cristiano y Humanista
                </span>
                <h1 class="font-display text-4xl sm:text-6xl lg:text-7xl font-black text-white leading-tight drop-shadow-lg uppercase">
                    RESPONSABILIDAD<br />SOCIAL
                </h1>
                <div class="w-24 h-1.5 bg-[#ae0c26] mx-auto rounded-full mt-6"></div>
            </div>
        </section>

        <div ref="sectionRef" class="font-sans">
            <!-- Bloque 1: Compartiendo alegría y esperanza en Uñas -->
            <section class="py-16 md:py-24 bg-white border-b border-neutral-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    
                    <div class="text-center mb-12 md:mb-16" data-reveal>
                        <span class="text-xs font-black uppercase tracking-[0.2em] text-[#ae0c26] block mb-2">
                            Campaña de Solidaridad
                        </span>
                        <h2 class="font-display text-3xl sm:text-4xl md:text-5xl font-black text-[#1a1d56]">
                            Compartiendo alegría y esperanza en Uñas
                        </h2>
                        <div class="w-20 h-1 bg-[#1a1d56] mx-auto rounded-full mt-4"></div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-center">
                        <div class="lg:col-span-6 space-y-6" data-reveal>
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-[#ae0c26]/10 text-[#ae0c26] text-xs font-bold uppercase tracking-wider">
                                <Heart class="size-4" />
                                <span>Amor al Prójimo</span>
                            </div>
                            <p class="text-neutral-600 text-base sm:text-lg leading-relaxed text-justify">
                                Como parte de nuestra Campaña de Solidaridad, los pequeños de 5 años vivieron una jornada
                                inolvidable de amor al prójimo. Entre risas, chocolatada y presentes, compartieron un tiempo
                                especial con los estudiantes de la I.E. N° 30003 "Pedro Rossel Díaz Huamán".
                            </p>
                            <p class="text-neutral-600 text-base sm:text-lg leading-relaxed text-justify">
                                Agradecemos profundamente a cada familia que hizo posible este gesto de ayuda cristiana,
                                demostrando que juntos podemos iluminar el corazón de quienes más lo necesitan.
                            </p>
                        </div>

                        <div class="lg:col-span-6" data-reveal>
                            <div class="rounded-3xl overflow-hidden shadow-2xl border border-neutral-100 group aspect-[4/3]">
                                <img src="/img/social/603840710_122188532090481352_2975100053160368900_n.jpg"
                                    alt="Campaña de Solidaridad en Uñas"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                            </div>
                        </div>
                    </div>

                </div>
            </section>

            <!-- Bloque 2: Solidaridad que Transforma -->
            <section class="py-16 md:py-24 bg-neutral-50/70 border-b border-neutral-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    
                    <div class="text-center mb-12 md:mb-16" data-reveal>
                        <span class="text-xs font-black uppercase tracking-[0.2em] text-[#ae0c26] block mb-2">
                            Promoción "Louis Broglie"
                        </span>
                        <h2 class="font-display text-3xl sm:text-4xl md:text-5xl font-black text-[#1a1d56]">
                            Solidaridad que Transforma
                        </h2>
                        <div class="w-20 h-1 bg-[#1a1d56] mx-auto rounded-full mt-4"></div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-center">
                        <div class="lg:col-span-6 order-2 lg:order-1" data-reveal>
                            <div class="rounded-3xl overflow-hidden shadow-2xl border border-neutral-100 group aspect-[4/3]">
                                <img src="/img/social/602979551_122188529294481352_6691042106099239542_n.jpg"
                                    alt="Solidaridad en CARE VIDAS e IREN Centro"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                            </div>
                        </div>

                        <div class="lg:col-span-6 order-1 lg:order-2 space-y-6" data-reveal>
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-[#1a1d56]/10 text-[#1a1d56] text-xs font-bold uppercase tracking-wider">
                                <Sparkles class="size-4" />
                                <span>Compromiso Social</span>
                            </div>
                            <p class="text-neutral-600 text-base sm:text-lg leading-relaxed text-justify">
                                En un acto de profunda empatía, los estudiantes de la promoción 2025 "Louis Broglie" llevaron
                                esperanza al Centro de Acogida Residencial Especializado (CARE VIDAS), brindando apoyo a
                                adolescentes gestantes y madres en situación de vulnerabilidad. Asimismo, extendieron su mano
                                solidaria al IREN Centro, acompañando a pacientes oncológicos y sus familias con el calor de la
                                Navidad.
                            </p>
                            <p class="text-neutral-600 text-base sm:text-lg leading-relaxed text-justify font-semibold text-[#1a1d56]">
                                Esta campaña reafirma nuestra labor de formar ciudadanos con gran calidad humana y compromiso social.
                            </p>
                        </div>
                    </div>

                </div>
            </section>

            <!-- Bloque 3: Ganador Bienestar y Salud LATIDO'S XXI -->
            <section class="py-16 md:py-24 bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    
                    <div class="text-center mb-12 md:mb-16" data-reveal>
                        <span class="text-xs font-black uppercase tracking-[0.2em] text-[#ae0c26] block mb-2">
                            Reconocimientos y Proyectos
                        </span>
                        <h2 class="font-display text-2xl sm:text-3xl md:text-4xl font-black text-[#1a1d56] max-w-4xl mx-auto leading-tight uppercase">
                            Ganador 2do Puesto Bienestar y Salud LATIDO'S XXI
                        </h2>
                        <div class="w-20 h-1 bg-[#1a1d56] mx-auto rounded-full mt-4"></div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-center">
                        <div class="lg:col-span-6" data-reveal>
                            <div class="rounded-3xl overflow-hidden shadow-2xl border border-neutral-100 group aspect-[4/3]">
                                <img src="/img/home/WhatsApp Image 2026-01-30 at 12.11.29 PM.jpeg"
                                    alt="Equipo LATIDO'S XXI"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                            </div>
                        </div>

                        <div class="lg:col-span-6 space-y-6" data-reveal>
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-amber-500/10 text-amber-700 text-xs font-bold uppercase tracking-wider">
                                <Award class="size-4" />
                                <span>Acción Hospitalaria</span>
                            </div>
                            <p class="text-neutral-600 text-base sm:text-lg leading-relaxed text-justify">
                                Los splashers del equipo «LATIDO´S XXI» realizaron presentaciones de clown, donaron peluches y
                                llevaron alegría, motivación y compañía a niños que se encontraban hospitalizados en el Hospital
                                Regional Ramiro Prialé Prialé (EsSalud), de la ciudad de Huancayo.
                            </p>
                            <p class="text-neutral-600 text-base sm:text-lg leading-relaxed text-justify">
                                El principal objetivo fue generar un espacio diferente dentro del hospital, donde los niños pudieron reír, soñar y olvidar por un momento el contexto médico en el que se encontraban.
                            </p>
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