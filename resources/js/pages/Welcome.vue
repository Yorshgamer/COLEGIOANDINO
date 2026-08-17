<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import WebLayout from '@/layouts/web/WebLayout.vue';
import { ArrowRight, GraduationCap, Award } from '@lucide/vue';
</script>

<template>

    <Head title="112 Años Educando Juventudes — Colegio Particular Andino" />

    <WebLayout>

        <!-- ══════════════════════════════════════════
             HERO
             ══════════════════════════════════════════ -->
        <section class="hero-section relative min-h-[calc(100vh-5rem)] flex items-center overflow-hidden bg-[#0f1240]">
            <div class="grid-bg absolute inset-0 z-0 pointer-events-none"></div>
            <div class="orb orb-red    absolute z-0 pointer-events-none"></div>
            <div class="orb orb-blue   absolute z-0 pointer-events-none"></div>
            <div class="orb orb-yellow absolute z-0 pointer-events-none"></div>
            <div
                class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-transparent via-[#af0c27] to-transparent z-10 pointer-events-none">
            </div>
            <div class="particles absolute inset-0 z-0 pointer-events-none" aria-hidden="true">
                <span v-for="i in 10" :key="i" :class="`particle particle-${i}`"></span>
            </div>
            <img src="/img/fondo-azul.png" alt="" aria-hidden="true"
                class="absolute inset-0 w-full h-full object-cover opacity-20 mix-blend-overlay pointer-events-none select-none z-0" />

            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 relative z-20 w-full py-20 lg:py-0">
                <div
                    class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center min-h-[60vh] lg:min-h-0 lg:py-16">

                    <!-- TEXTO -->
                    <div class="lg:col-span-6 text-center lg:text-left space-y-6 order-2 lg:order-1 animate-slide-left">
                        <div class="inline-flex items-center gap-2.5 justify-center lg:justify-start">
                            <span class="eyebrow-dot"></span>
                            <span class="text-[10px] sm:text-[11px] font-bold tracking-[3px] uppercase text-white/50">
                                Bienvenidos al Colegio Andino
                            </span>
                        </div>
                        <h1
                            class="text-[40px] sm:text-[52px] lg:text-[62px] xl:text-[68px] font-black text-white leading-[1.03] tracking-tight">
                            Desde 1913,<br>formando<br>
                            <span class="hero-highlight relative inline-block text-[#af0c27]">generaciones</span><br>
                            con valores
                        </h1>
                        <p
                            class="text-white/60 text-sm sm:text-base lg:text-[16.5px] leading-[1.8] max-w-[440px] mx-auto lg:mx-0 animate-fade-up-delay-2">
                            Lideramos la educación en Huancayo con una sólida tradición humanista y excelencia académica
                            comprobada.
                        </p>
                        <div
                            class="flex flex-col sm:flex-row justify-center lg:justify-start gap-3.5 pt-2 animate-fade-up-delay-3">
                            <Link href="#"
                                class="hero-btn-primary group inline-flex items-center justify-center gap-2.5 rounded-full bg-[#af0c27] px-8 py-4 text-[13px] font-black tracking-wide text-white transition-all duration-300 hover:-translate-y-0.5">
                                <span>Postula aquí</span>
                                <ArrowRight
                                    class="size-4 transition-transform duration-300 group-hover:translate-x-1" />
                            </Link>
                            <Link href="#"
                                class="inline-flex items-center justify-center gap-2.5 rounded-full border border-white/20 bg-white/5 px-8 py-4 text-[13px] font-bold text-white/80 backdrop-blur-sm transition-all duration-300 hover:border-white/40 hover:bg-white/10 hover:text-white">
                                Nuestra Historia
                            </Link>
                        </div>
                        <!-- Stats -->
                        <div
                            class="flex flex-wrap items-center justify-center lg:justify-start gap-x-7 gap-y-4 pt-8 mt-2 border-t border-white/8 animate-fade-up-delay-4">
                            <div class="text-center lg:text-left">
                                <div class="text-[28px] font-black text-white leading-none">112</div>
                                <div class="text-[10px] font-semibold tracking-widest text-white/35 uppercase mt-1">Años
                                </div>
                            </div>
                            <div class="w-px h-10 bg-white/10 hidden sm:block"></div>
                            <div class="text-center lg:text-left">
                                <div class="text-[28px] font-black text-white leading-none">798</div>
                                <div class="text-[10px] font-semibold tracking-widest text-white/35 uppercase mt-1">
                                    Alumnos</div>
                            </div>
                            <div class="w-px h-10 bg-white/10 hidden sm:block"></div>
                            <div class="text-center lg:text-left">
                                <div class="text-[28px] font-black text-white leading-none">300</div>
                                <div class="text-[10px] font-semibold tracking-widest text-white/35 uppercase mt-1">
                                    Promociones</div>
                            </div>
                            <div class="w-px h-10 bg-white/10 hidden sm:block"></div>
                            <div class="text-center lg:text-left">
                                <div class="text-[28px] font-black text-white leading-none">4,663</div>
                                <div class="text-[10px] font-semibold tracking-widest text-white/35 uppercase mt-1">m²
                                    de área</div>
                            </div>
                        </div>
                    </div>

                    <!-- IMAGEN -->
                    <div class="lg:col-span-6 relative flex justify-center order-1 lg:order-2 animate-slide-right">
                        <div
                            class="ring ring-outer absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none">
                        </div>
                        <div
                            class="ring ring-inner absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none">
                        </div>
                        <div class="orbital-dot absolute top-1/2 left-1/2 pointer-events-none"></div>
                        <img class="relative z-10 w-auto max-h-[420px] sm:max-h-[500px] lg:max-h-[600px] object-contain drop-shadow-[0_30px_60px_rgba(0,0,0,0.5)] select-none pointer-events-none animate-float"
                            src="/img/banner.png" alt="Estudiante Colegio Andino" />
                        <!-- Card 1 -->
                        <div
                            class="float-card absolute bottom-12 -left-4 lg:-left-6 z-20 flex items-center gap-3 rounded-2xl bg-white px-4 py-3.5 shadow-[0_8px_32px_rgba(0,0,0,0.18)] animate-float-card-1">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-50 text-xl">🏆
                            </div>
                            <div>
                                <p class="text-[16px] font-black text-neutral-900 leading-none">N° 1</p>
                                <p class="text-[10px] font-bold text-neutral-400 mt-0.5 tracking-wide uppercase">Junín
                                </p>
                            </div>
                        </div>
                        <!-- Card 2 -->
                        <div
                            class="float-card absolute top-8 -right-4 lg:-right-4 z-20 flex items-center gap-3 rounded-2xl bg-white px-4 py-3.5 shadow-[0_8px_32px_rgba(0,0,0,0.18)] animate-float-card-2">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-xl">🎓
                            </div>
                            <div>
                                <p class="text-[16px] font-black text-neutral-900 leading-none">100%</p>
                                <p class="text-[10px] font-bold text-neutral-400 mt-0.5 tracking-wide uppercase">
                                    Excelencia</p>
                            </div>
                            <span
                                class="absolute -top-1.5 -right-1.5 flex size-4 items-center justify-center rounded-full bg-[#af0c27]">
                                <span
                                    class="absolute inline-flex size-full rounded-full bg-[#af0c27] animate-ping opacity-60"></span>
                                <span class="relative inline-flex size-2 rounded-full bg-white"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scroll hint -->
            <div
                class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 flex flex-col items-center gap-2 animate-fade-up-delay-4">
                <div
                    class="scroll-mouse flex h-9 w-6 items-start justify-center rounded-xl border border-white/20 pt-1.5">
                    <span class="scroll-wheel block h-1.5 w-0.5 rounded-full bg-white/40"></span>
                </div>
                <span class="text-[9px] font-semibold tracking-[3px] uppercase text-white/25">Scroll</span>
            </div>
        </section>

        <!-- ══════════════════════════════════════════
             SECCIÓN 2 — ACERCA DE
             Fondo: blanco cálido, sin dark mode para evitar
             problema con PNGs de fondo negro
             ══════════════════════════════════════════ -->
        <section class="py-24 bg-[#faf9f7] overflow-hidden">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

                    <!-- Imagen con marco decorativo -->
                    <div class="relative group">
                        <!-- Marco rojo desplazado -->
                        <div
                            class="absolute -bottom-4 -right-4 w-full h-full rounded-3xl border-2 border-[#af0c27]/20 pointer-events-none">
                        </div>
                        <!-- Punto decorativo esquina -->
                        <div class="absolute -top-3 -left-3 w-6 h-6 rounded-full bg-[#af0c27] z-10"></div>
                        <div class="relative overflow-hidden rounded-2xl shadow-[0_20px_60px_rgba(0,0,0,0.1)]">
                            <img class="w-full h-auto object-cover transform group-hover:scale-[1.03] transition-transform duration-700 ease-out select-none"
                                src="/img/seccion.jpg" alt="Colegio Andino - Excelencia Educativa" />
                            <!-- Etiqueta superpuesta -->
                            <div
                                class="absolute bottom-5 left-5 flex items-center gap-3 bg-white/95 backdrop-blur-sm rounded-xl px-4 py-3 shadow-lg">
                                <div class="w-2 h-2 rounded-full bg-[#af0c27] animate-pulse"></div>
                                <span class="text-[11px] font-black uppercase tracking-widest text-neutral-700">Desde
                                    1913</span>
                            </div>
                        </div>
                    </div>

                    <!-- Texto -->
                    <div class="space-y-6">
                        <!-- Eyebrow -->
                        <div class="flex items-center gap-3">
                            <div class="h-px w-10 bg-[#af0c27]"></div>
                            <span class="text-[10px] font-black tracking-[3px] uppercase text-[#af0c27]">Quiénes
                                somos</span>
                        </div>
                        <h2 class="text-4xl sm:text-5xl font-black text-[#192A56] leading-tight tracking-tight">
                            Excelencia educativa<br>
                            <span class="text-[#af0c27]">con experiencia</span>
                        </h2>
                        <p class="text-neutral-500 text-base leading-[1.85]">
                            Nuestro Colegio forma parte de una amplia tradición pedagógica y humanista, brindando una
                            formación integral enfocada en el óptimo desarrollo intelectual y cimentada sobre valores
                            sólidos para la vida.
                        </p>

                        <!-- Mini-features -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                            <div
                                class="flex items-start gap-3 p-4 rounded-xl bg-white border border-neutral-100 shadow-xs">
                                <div
                                    class="mt-0.5 flex-shrink-0 w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center">
                                    <GraduationCap class="size-4 text-[#af0c27]" />
                                </div>
                                <div>
                                    <p class="text-sm font-black text-neutral-800">Formación integral</p>
                                    <p class="text-xs text-neutral-400 mt-0.5 leading-relaxed">Académica, espiritual y
                                        humana</p>
                                </div>
                            </div>
                            <div
                                class="flex items-start gap-3 p-4 rounded-xl bg-white border border-neutral-100 shadow-xs">
                                <div
                                    class="mt-0.5 flex-shrink-0 w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                                    <Award class="size-4 text-[#192A56]" />
                                </div>
                                <div>
                                    <p class="text-sm font-black text-neutral-800">Alto rendimiento</p>
                                    <p class="text-xs text-neutral-400 mt-0.5 leading-relaxed">Certificaciones
                                        internacionales</p>
                                </div>
                            </div>
                        </div>

                        <Link href="#"
                            class="inline-flex items-center gap-2 text-[13px] font-black text-[#af0c27] hover:gap-3 transition-all duration-200 group">
                            <span>Descubre más sobre nosotros</span>
                            <ArrowRight class="size-4" />
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- ══════════════════════════════════════════
             SECCIÓN 3 — NIVELES EDUCATIVOS
             ══════════════════════════════════════════ -->
        <section class="py-24 bg-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                <!-- Header -->
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <div class="inline-flex items-center gap-3 mb-4">
                        <div class="h-px w-8 bg-[#af0c27]"></div>
                        <span class="text-[10px] font-black tracking-[3px] uppercase text-[#af0c27]">Oferta
                            académica</span>
                        <div class="h-px w-8 bg-[#af0c27]"></div>
                    </div>
                    <h2 class="text-4xl sm:text-5xl font-black text-[#192A56] tracking-tight leading-tight">
                        Niveles Educativos
                    </h2>
                    <p class="text-neutral-400 text-sm mt-4 leading-relaxed">
                        Educación de calidad para inspirar a líderes capaces de crear un impacto positivo en el mundo.
                    </p>
                </div>

                <!-- Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <!-- Inicial -->
                    <Link href="#"
                        class="nivel-card group relative flex flex-col rounded-2xl overflow-hidden border border-neutral-100 bg-white shadow-xs hover:shadow-xl transition-all duration-400 hover:-translate-y-1.5">
                        <div class="relative aspect-[4/3] overflow-hidden">
                            <img src="/img/home/ninna.jpg" alt="Educación Inicial"
                                class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-600 ease-out" />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-[#192A56]/70 via-[#192A56]/10 to-transparent">
                            </div>
                            <!-- Número decorativo -->
                            <div
                                class="absolute top-4 left-4 w-9 h-9 rounded-full bg-white/15 backdrop-blur-sm border border-white/30 flex items-center justify-center">
                                <span class="text-[11px] font-black text-white">01</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between gap-4 px-6 py-5">
                            <div>
                                <p class="text-[10px] font-bold tracking-widest text-[#af0c27] uppercase mb-1">Nivel</p>
                                <h5 class="text-sm font-extrabold text-neutral-800 uppercase tracking-wider">Educación
                                    Inicial</h5>
                            </div>
                            <div
                                class="flex-shrink-0 flex size-9 items-center justify-center rounded-full border border-neutral-100 bg-neutral-50 text-neutral-300 group-hover:bg-[#af0c27] group-hover:border-[#af0c27] group-hover:text-white transition-all duration-300">
                                <ArrowRight class="size-4" />
                            </div>
                        </div>
                    </Link>

                    <!-- Primaria -->
                    <Link href="#"
                        class="nivel-card group relative flex flex-col rounded-2xl overflow-hidden border border-neutral-100 bg-white shadow-xs hover:shadow-xl transition-all duration-400 hover:-translate-y-1.5">
                        <div class="relative aspect-[4/3] overflow-hidden">
                            <img src="/img/home/biblioteca.jpg" alt="Educación Primaria"
                                class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-600 ease-out" />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-[#192A56]/70 via-[#192A56]/10 to-transparent">
                            </div>
                            <div
                                class="absolute top-4 left-4 w-9 h-9 rounded-full bg-white/15 backdrop-blur-sm border border-white/30 flex items-center justify-center">
                                <span class="text-[11px] font-black text-white">02</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between gap-4 px-6 py-5">
                            <div>
                                <p class="text-[10px] font-bold tracking-widest text-[#af0c27] uppercase mb-1">Nivel</p>
                                <h5 class="text-sm font-extrabold text-neutral-800 uppercase tracking-wider">Educación
                                    Primaria</h5>
                            </div>
                            <div
                                class="flex-shrink-0 flex size-9 items-center justify-center rounded-full border border-neutral-100 bg-neutral-50 text-neutral-300 group-hover:bg-[#af0c27] group-hover:border-[#af0c27] group-hover:text-white transition-all duration-300">
                                <ArrowRight class="size-4" />
                            </div>
                        </div>
                    </Link>

                    <!-- Secundaria -->
                    <Link href="#"
                        class="nivel-card group relative flex flex-col rounded-2xl overflow-hidden border border-neutral-100 bg-white shadow-xs hover:shadow-xl transition-all duration-400 hover:-translate-y-1.5">
                        <div class="relative aspect-[4/3] overflow-hidden">
                            <img src="/img/home/ensenando.jpg" alt="Educación Secundaria"
                                class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-600 ease-out" />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-[#192A56]/70 via-[#192A56]/10 to-transparent">
                            </div>
                            <div
                                class="absolute top-4 left-4 w-9 h-9 rounded-full bg-white/15 backdrop-blur-sm border border-white/30 flex items-center justify-center">
                                <span class="text-[11px] font-black text-white">03</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between gap-4 px-6 py-5">
                            <div>
                                <p class="text-[10px] font-bold tracking-widest text-[#af0c27] uppercase mb-1">Nivel</p>
                                <h5 class="text-sm font-extrabold text-neutral-800 uppercase tracking-wider">Educación
                                    Secundaria</h5>
                            </div>
                            <div
                                class="flex-shrink-0 flex size-9 items-center justify-center rounded-full border border-neutral-100 bg-neutral-50 text-neutral-300 group-hover:bg-[#af0c27] group-hover:border-[#af0c27] group-hover:text-white transition-all duration-300">
                                <ArrowRight class="size-4" />
                            </div>
                        </div>
                    </Link>
                </div>
            </div>
        </section>

        <!-- ══════════════════════════════════════════
             SECCIÓN 4 — RECONOCIMIENTOS
             Fondo claro para que los logos PNGs se vean
             ══════════════════════════════════════════ -->
        <section class="py-24 bg-[#faf9f7]">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

                    <!-- Texto + Logos -->
                    <div class="space-y-8">
                        <div>
                            <div class="flex items-center gap-3 mb-4">
                                <div class="h-px w-10 bg-[#af0c27]"></div>
                                <span
                                    class="text-[10px] font-black tracking-[3px] uppercase text-[#af0c27]">Reconocimientos</span>
                            </div>
                            <h2
                                class="text-4xl sm:text-5xl font-black text-[#192A56] tracking-tight leading-tight mb-5">
                                Somos <span class="text-[#af0c27]">reconocidos</span>
                            </h2>
                            <p class="text-neutral-500 text-base leading-[1.85]">
                                Por la excelencia académica y el alto rendimiento de nuestros estudiantes, reflejado en
                                el impecable desempeño en los exámenes internacionales de Certificación del Idioma
                                Inglés y en las óptimas prácticas formativas impartidas por nuestro plantel.
                            </p>
                        </div>

                        <!-- Logos en grilla con fondo blanco sólido -->
                        <!-- Logos en estructura de pirámide invertida y más grandes -->
                        <div>
                            <p class="text-[10px] font-bold tracking-widest uppercase text-neutral-400 mb-5">Alianzas y
                                reconocimientos</p>

                            <!-- Contenedor Flex que agrupa y centra los elementos creando el efecto pirámide -->
                            <div class="flex flex-wrap justify-center gap-4 max-w-xl">
                                <div v-for="(logo, index) in [
                                    { src: '/img/colegio/HOMEMIN.png', alt: 'Minedu' },
                                    { src: '/img/colegio/HOMEPUCP.png', alt: 'PUCP' },
                                    { src: '/img/colegio/HOMEPAC.png', alt: 'Pacífico' },
                                    { src: '/img/colegio/HOMECONTI.png', alt: 'Continental' },
                                    { src: '/img/colegio/HOMEUSIL.png', alt: 'USIL' },
                                ]" :key="logo.alt" :class="[
                                    'group flex items-center justify-center rounded-2xl bg-white border border-neutral-100 px-6 shadow-xs hover:shadow-md hover:border-[#af0c27]/20 transition-all duration-300 cursor-pointer h-20',
                                    /* Las 3 primeras ocupan un ancho base, las 2 últimas se adaptan abajo automáticamente */
                                    index < 3 ? 'w-[calc(33.33%-11px)] min-w-[130px]' : 'w-[calc(50%-8px)] min-w-[150px]'
                                ]">
                                    <img :src="logo.src" :alt="logo.alt"
                                        class="max-h-12 w-auto object-contain grayscale opacity-60 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-300" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Imagen de reconocimiento — bg blanco para que el PNG se vea -->
                    <div class="flex justify-center">
                        <div class="relative">
                            <!-- Fondo decorativo -->
                            <div class="absolute inset-6 rounded-3xl bg-[#192A56]/5"></div>
                            <div
                                class="relative bg-white rounded-2xl border border-neutral-100 shadow-[0_20px_60px_rgba(0,0,0,0.07)] p-6">
                                <img class="w-full h-auto object-contain max-w-md" src="/img/colegio/HOME6.png"
                                    alt="Reconocimientos Colegio Andino" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ══════════════════════════════════════════
             SECCIÓN 5 — ALIANZAS / RED
             Franja limpia con logos sobre blanco
             ══════════════════════════════════════════ -->
        <section class="py-14 bg-white border-t border-neutral-100">
            <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
                <p class="text-center text-[10px] font-black tracking-[4px] uppercase text-neutral-300 mb-10">
                    Somos parte de la red de colegios y partícipes de
                </p>
                <div class="flex flex-wrap justify-center items-center gap-12 lg:gap-20">
                    <img src="/img/home/LPDR.png" alt="Liga Peruana de Debate Escolar"
                        class="h-16 w-auto object-contain opacity-50 hover:opacity-100 transition-opacity duration-300" />
                    <div class="w-px h-10 bg-neutral-100 hidden sm:block"></div>
                    <img src="/img/home/ALIANZA FRANCESA LOGO-02.jpg" alt="Alliance Française"
                        class="h-16 w-auto object-contain opacity-50 hover:opacity-100 transition-opacity duration-300" />
                </div>
            </div>
        </section>

        <!-- ══════════════════════════════════════════
     SECCIÓN 6 — VALORES TRIPARTITOS
     Fondo azul oscuro propio para que las PNGs
     con fondo negro/transparente se integren bien
     ══════════════════════════════════════════ -->
<section class="relative overflow-hidden">
    <!-- Franja decorativa superior -->
    <div class="h-1 w-full bg-gradient-to-r from-[#192A56] via-[#af0c27] to-[#192A56]"></div>

    <div class="grid grid-cols-1 lg:grid-cols-3">

        <!-- Panel 1: Tradición -->
        <div class="relative flex flex-col items-center justify-center text-center px-10 py-20 bg-[#1b1d56] group overflow-hidden">
            <div class="absolute inset-0 bg-white/0 group-hover:bg-white/3 transition-colors duration-500"></div>
            <!-- Número fondo decorativo -->
            <span class="absolute right-6 bottom-4 text-[80px] font-black text-white/4 leading-none select-none">01</span>
            
            <div class="relative z-10 flex flex-col items-center gap-5">
                <!-- Contenedor optimizado: más ancho, h-auto y escala limpia -->
                <div class="w-32 h-auto flex items-center justify-center group-hover:scale-108 transition-transform duration-400 ease-out">
                    <img src="/img/tradicion.png" alt="Tradición" class="w-full h-auto object-contain filter drop-shadow-xl" />
                </div>
                <div>
                    <p class="text-[9px] font-bold tracking-[3px] text-white/40 uppercase mb-2">Pilar 01</p>
                    <h3 class="text-lg font-black text-white tracking-wide uppercase">Tradición</h3>
                    <p class="text-sm font-semibold text-white/50 mt-1">con propósito</p>
                </div>
            </div>
        </div>

        <!-- Panel 2: Excelencia — rojo, resaltado -->
        <div class="relative flex flex-col items-center justify-center text-center px-10 py-20 bg-[#af0c27] group overflow-hidden">
            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/8 transition-colors duration-500"></div>
            <span class="absolute right-6 bottom-4 text-[80px] font-black text-white/5 leading-none select-none">02</span>
            <!-- Brillo superior -->
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-32 h-1 bg-white/20 rounded-b-full"></div>
            
            <div class="relative z-10 flex flex-col items-center gap-5">
                <!-- Contenedor optimizado: más ancho, h-auto y escala limpia -->
                <div class="w-32 h-auto flex items-center justify-center group-hover:scale-108 transition-transform duration-400 ease-out">
                    <img src="/img/exelencia.png" alt="Excelencia" class="w-full h-auto object-contain filter drop-shadow-xl" />
                </div>
                <div>
                    <p class="text-[9px] font-bold tracking-[3px] text-white/50 uppercase mb-2">Pilar 02</p>
                    <h3 class="text-lg font-black text-white tracking-wide uppercase">Excelencia</h3>
                    <p class="text-sm font-semibold text-white/60 mt-1">educativa</p>
                </div>
            </div>
        </div>

        <!-- Panel 3: Proyección -->
        <div class="relative flex flex-col items-center justify-center text-center px-10 py-20 bg-[#1b1d56] group overflow-hidden">
            <div class="absolute inset-0 bg-white/0 group-hover:bg-white/3 transition-colors duration-500"></div>
            <span class="absolute right-6 bottom-4 text-[80px] font-black text-white/4 leading-none select-none">03</span>
            
            <div class="relative z-10 flex flex-col items-center gap-5">
                <!-- Contenedor optimizado: más ancho, h-auto y escala limpia -->
                <div class="w-32 h-auto flex items-center justify-center group-hover:scale-108 transition-transform duration-400 ease-out">
                    <img src="/img/proyeccion.png" alt="Proyección" class="w-full h-auto object-contain filter drop-shadow-xl" />
                </div>
                <div>
                    <p class="text-[9px] font-bold tracking-[3px] text-white/40 uppercase mb-2">Pilar 03</p>
                    <h3 class="text-lg font-black text-white tracking-wide uppercase">Proyección</h3>
                    <p class="text-sm font-semibold text-white/50 mt-1">al futuro</p>
                </div>
            </div>
        </div>

    </div>
</section>

    </WebLayout>
</template>

<style scoped>
/* ── HERO ── */
.grid-bg {
    background-image:
        linear-gradient(rgba(255, 255, 255, 0.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, 0.025) 1px, transparent 1px);
    background-size: 60px 60px;
    -webkit-mask-image: radial-gradient(ellipse 90% 90% at 50% 50%, black 0%, transparent 100%);
    mask-image: radial-gradient(ellipse 90% 90% at 50% 50%, black 0%, transparent 100%);
}

.orb {
    border-radius: 50%;
    filter: blur(90px);
    animation: orb-breathe ease-in-out infinite alternate;
}

.orb-red {
    width: 600px;
    height: 600px;
    background: rgba(175, 12, 39, 0.13);
    right: -150px;
    bottom: -150px;
    animation-duration: 7s;
}

.orb-blue {
    width: 450px;
    height: 450px;
    background: rgba(70, 90, 200, 0.09);
    left: -100px;
    top: -100px;
    animation-duration: 9s;
    animation-delay: -3s;
}

.orb-yellow {
    width: 300px;
    height: 300px;
    background: rgba(255, 200, 0, 0.04);
    left: 45%;
    top: 45%;
    animation-duration: 12s;
    animation-delay: -6s;
}

@keyframes orb-breathe {
    from {
        transform: scale(1);
        opacity: 0.5;
    }

    to {
        transform: scale(1.25);
        opacity: 1;
    }
}

.particle {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.07);
    animation: particle-rise linear infinite;
}

.particle-1 {
    width: 6px;
    height: 6px;
    left: 7%;
    animation-duration: 13s;
    animation-delay: 0s;
    top: 85%;
}

.particle-2 {
    width: 3px;
    height: 3px;
    left: 17%;
    animation-duration: 19s;
    animation-delay: -4s;
    top: 65%;
}

.particle-3 {
    width: 8px;
    height: 8px;
    left: 28%;
    animation-duration: 15s;
    animation-delay: -8s;
    top: 90%;
}

.particle-4 {
    width: 4px;
    height: 4px;
    left: 43%;
    animation-duration: 21s;
    animation-delay: -2s;
    top: 78%;
}

.particle-5 {
    width: 5px;
    height: 5px;
    left: 56%;
    animation-duration: 17s;
    animation-delay: -11s;
    top: 95%;
}

.particle-6 {
    width: 3px;
    height: 3px;
    left: 67%;
    animation-duration: 12s;
    animation-delay: -6s;
    top: 72%;
}

.particle-7 {
    width: 6px;
    height: 6px;
    left: 76%;
    animation-duration: 16s;
    animation-delay: -1s;
    top: 88%;
}

.particle-8 {
    width: 2px;
    height: 2px;
    left: 84%;
    animation-duration: 23s;
    animation-delay: -14s;
    top: 60%;
}

.particle-9 {
    width: 4px;
    height: 4px;
    left: 91%;
    animation-duration: 14s;
    animation-delay: -9s;
    top: 80%;
}

.particle-10 {
    width: 3px;
    height: 3px;
    left: 33%;
    animation-duration: 18s;
    animation-delay: -5s;
    top: 55%;
}

@keyframes particle-rise {
    0% {
        transform: translateY(0) rotate(0deg);
        opacity: 0;
    }

    8% {
        opacity: 1;
    }

    92% {
        opacity: 1;
    }

    100% {
        transform: translateY(-100vh) rotate(720deg);
        opacity: 0;
    }
}

.eyebrow-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #af0c27;
    flex-shrink: 0;
    animation: eyebrow-pulse 2.2s ease-in-out infinite;
}

@keyframes eyebrow-pulse {

    0%,
    100% {
        box-shadow: 0 0 0 0 rgba(175, 12, 39, 0.7);
    }

    50% {
        box-shadow: 0 0 0 7px rgba(175, 12, 39, 0);
    }
}

.hero-highlight::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #af0c27, rgba(175, 12, 39, 0.2));
    border-radius: 2px;
    transform: scaleX(0);
    transform-origin: left;
    animation: underline-reveal 1s cubic-bezier(0.16, 1, 0.3, 1) 0.9s both;
}

@keyframes underline-reveal {
    to {
        transform: scaleX(1);
    }
}

.hero-btn-primary {
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(175, 12, 39, 0.35), 0 1px 4px rgba(0, 0, 0, 0.3);
}

.hero-btn-primary::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.12) 0%, transparent 60%);
}

.hero-btn-primary:hover {
    box-shadow: 0 8px 28px rgba(175, 12, 39, 0.45), 0 2px 8px rgba(0, 0, 0, 0.3);
}

.hero-btn-primary:active {
    transform: translateY(0) scale(0.98);
}

.ring {
    border-radius: 50%;
    border: 1px solid rgba(255, 255, 255, 0.05);
    pointer-events: none;
}

.ring-outer {
    width: 440px;
    height: 440px;
    animation: ring-cw 28s linear infinite;
}

.ring-inner {
    width: 320px;
    height: 320px;
    border-style: dashed;
    border-color: rgba(175, 12, 39, 0.1);
    animation: ring-ccw 20s linear infinite;
}

@keyframes ring-cw {
    to {
        transform: translate(-50%, -50%) rotate(360deg);
    }
}

@keyframes ring-ccw {
    to {
        transform: translate(-50%, -50%) rotate(-360deg);
    }
}

.orbital-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #af0c27;
    box-shadow: 0 0 12px rgba(175, 12, 39, 0.8);
    animation: orbit 28s linear infinite;
}

.orbital-dot::after {
    content: '';
    position: absolute;
    inset: -5px;
    border-radius: 50%;
    background: rgba(175, 12, 39, 0.25);
    animation: dot-glow 2.5s ease-in-out infinite;
}

@keyframes orbit {
    from {
        transform: translate(-50%, -50%) rotate(0deg) translateX(220px) rotate(0deg);
    }

    to {
        transform: translate(-50%, -50%) rotate(360deg) translateX(220px) rotate(-360deg);
    }
}

@keyframes dot-glow {

    0%,
    100% {
        transform: scale(1);
        opacity: 1;
    }

    50% {
        transform: scale(2.2);
        opacity: 0;
    }
}

@keyframes float-img {

    0%,
    100% {
        transform: translateY(0px);
    }

    50% {
        transform: translateY(-12px);
    }
}

.animate-float {
    animation: float-img 6s ease-in-out infinite;
}

@keyframes float-card-a {

    0%,
    100% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(-8px);
    }
}

@keyframes float-card-b {

    0%,
    100% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(-10px);
    }
}

.animate-float-card-1 {
    animation: float-card-a 4.5s ease-in-out infinite;
}

.animate-float-card-2 {
    animation: float-card-b 5.5s ease-in-out infinite;
    animation-delay: -2s;
}

.scroll-wheel {
    animation: scroll-bob 2s ease-in-out infinite;
}

@keyframes scroll-bob {
    0% {
        transform: translateY(0);
        opacity: 1;
    }

    80% {
        transform: translateY(8px);
        opacity: 0;
    }

    100% {
        transform: translateY(0);
        opacity: 0;
    }
}

.animate-slide-left {
    animation: slide-in-left 0.85s cubic-bezier(0.16, 1, 0.3, 1) both;
}

.animate-slide-right {
    animation: slide-in-right 0.85s cubic-bezier(0.16, 1, 0.3, 1) 0.1s both;
}

@keyframes slide-in-left {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }

    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slide-in-right {
    from {
        opacity: 0;
        transform: translateX(30px);
    }

    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.animate-fade-up-delay-2 {
    animation: fade-up 0.7s ease-out 0.4s both;
}

.animate-fade-up-delay-3 {
    animation: fade-up 0.7s ease-out 0.6s both;
}

.animate-fade-up-delay-4 {
    animation: fade-up 0.7s ease-out 0.8s both;
}

@keyframes fade-up {
    from {
        opacity: 0;
        transform: translateY(18px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* ── NIVELES ── */
.nivel-card {
    transition-duration: 400ms;
}

.duration-400 {
    transition-duration: 400ms;
}

.duration-600 {
    transition-duration: 600ms;
}
</style>