<script setup lang="ts">
import WebLayout from '@/layouts/web/WebLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import { MapPin, Mail, Phone, Smartphone, Send, CheckCircle2 } from '@lucide/vue';

defineProps<{
    title?: string;
}>();

const sectionRef = ref<HTMLElement | null>(null);
const showModal = ref(false);

// Formulario reactivo para enviar mensajes
const form = useForm({
    name: '',
    email: '',
    phone: '',
    subject: '',
    message: '',
});

const submitForm = () => {
    // Provisorio: Muestra el modal de confirmación visual
    // Cuando configuremos el backend, usaremos: form.post(route('contacto.send'), ...)
    showModal.value = true;
    form.reset();
};

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

    <Head :title="title || 'Contacto - Colegio Andino'" />

    <WebLayout>
        <!-- Header con Breadcrumb -->
        <section
            class="relative bg-gradient-to-b from-neutral-100 to-white py-14 md:py-20 border-b border-neutral-200/60 overflow-hidden">
            <div
                class="absolute top-0 left-1/2 -translate-x-1/2 w-24 h-1 bg-gradient-to-r from-[#1a1d56] to-[#ae0c26] rounded-full">
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <span class="text-xs font-bold uppercase tracking-[0.25em] text-[#ae0c26]/80 block mb-3 font-sans">
                    Atención a Familias
                </span>
                <h1 class="font-display text-4xl md:text-6xl font-black text-[#1a1d56] tracking-tight mb-4">
                    Contacto
                </h1>
                <nav aria-label="Breadcrumb"
                    class="flex justify-center items-center gap-2 text-sm font-semibold font-sans">
                    <Link href="/"
                        class="text-[#ae0c26] hover:underline transition-all focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#ae0c26] rounded-sm">
                        Inicio
                    </Link>
                    <span class="text-neutral-400">/</span>
                    <span class="text-neutral-500">Contacto</span>
                </nav>
            </div>
        </section>

        <!-- Sección de Contenido Principal -->
        <section ref="sectionRef" class="py-16 md:py-24 bg-white font-sans">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <!-- Primera Fila: Datos de Contacto + Formulario -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start mb-16 md:mb-20">

                    <!-- Columna Izquierda - Información de Contacto -->
                    <div class="lg:col-span-5 space-y-8" data-reveal>
                        <div>
                            <span class="text-xs font-black uppercase tracking-[0.2em] text-[#ae0c26] block mb-2">
                                Canales Oficiales
                            </span>
                            <h2 class="font-display text-3xl md:text-4xl font-black text-[#1a1d56]">
                                Estamos para ayudarte
                            </h2>
                            <div class="w-16 h-1 bg-[#1a1d56] rounded-full mt-3"></div>
                        </div>

                        <!-- Ubicación -->
                        <div
                            class="flex items-start gap-4 p-4 rounded-2xl bg-neutral-50 border border-neutral-100/80 transition-all hover:border-neutral-200">
                            <div
                                class="w-12 h-12 rounded-xl bg-[#ae0c26]/10 flex items-center justify-center text-[#ae0c26] shrink-0 mt-0.5">
                                <MapPin class="size-6" />
                            </div>
                            <div>
                                <h3 class="font-display font-bold text-[#1a1d56] text-lg mb-1">Ubicación</h3>
                                <p class="text-neutral-600 text-base">Jr. Guido N° 512 - Huancayo</p>
                            </div>
                        </div>

                        <!-- Correo -->
                        <div
                            class="flex items-start gap-4 p-4 rounded-2xl bg-neutral-50 border border-neutral-100/80 transition-all hover:border-neutral-200">
                            <div
                                class="w-12 h-12 rounded-xl bg-[#ae0c26]/10 flex items-center justify-center text-[#ae0c26] shrink-0 mt-0.5">
                                <Mail class="size-6" />
                            </div>
                            <div>
                                <h3 class="font-display font-bold text-[#1a1d56] text-lg mb-1">Correo Electrónico</h3>
                                <a href="mailto:secretaria@andino.edu.pe"
                                    class="text-neutral-600 text-base hover:text-[#ae0c26] transition-colors">
                                    secretaria@andino.edu.pe
                                </a>
                            </div>
                        </div>

                        <!-- Teléfono Fijo -->
                        <div
                            class="flex items-start gap-4 p-4 rounded-2xl bg-neutral-50 border border-neutral-100/80 transition-all hover:border-neutral-200">
                            <div
                                class="w-12 h-12 rounded-xl bg-[#ae0c26]/10 flex items-center justify-center text-[#ae0c26] shrink-0 mt-0.5">
                                <Phone class="size-6" />
                            </div>
                            <div>
                                <h3 class="font-display font-bold text-[#1a1d56] text-lg mb-1">Teléfono Fijo</h3>
                                <a href="tel:064238521"
                                    class="text-neutral-600 text-base hover:text-[#ae0c26] transition-colors">
                                    064 232521
                                </a>
                            </div>
                        </div>

                        <!-- Celular -->
                        <div
                            class="flex items-start gap-4 p-4 rounded-2xl bg-neutral-50 border border-neutral-100/80 transition-all hover:border-neutral-200">
                            <div
                                class="w-12 h-12 rounded-xl bg-[#ae0c26]/10 flex items-center justify-center text-[#ae0c26] shrink-0 mt-0.5">
                                <Smartphone class="size-6" />
                            </div>
                            <div>
                                <h3 class="font-display font-bold text-[#1a1d56] text-lg mb-1">Celular</h3>
                                <a href="tel:944688081"
                                    class="text-neutral-600 text-base hover:text-[#ae0c26] transition-colors">
                                    944 688 081
                                </a>
                            </div>
                        </div>

                        <!-- Redes Sociales -->
                        <div class="pt-2">
                            <h3 class="font-display font-bold text-[#1a1d56] text-lg mb-4">
                                Síguenos en redes
                            </h3>
                            <div class="flex items-center gap-3 flex-wrap">
                                <!-- Facebook -->
                                <a href="https://www.facebook.com/colegio.particular.andino" target="_blank"
                                    rel="noopener noreferrer" aria-label="Facebook Colegio Andino"
                                    class="w-12 h-12 rounded-xl bg-[#ae0c26] text-white flex items-center justify-center hover:bg-[#1a1d56] hover:scale-105 transition-all duration-300 shadow-md">
                                    <svg class="size-6 fill-current" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                    </svg>
                                </a>

                                <!-- YouTube -->
                                <a href="https://www.youtube.com/@colegioandino1575" target="_blank"
                                    rel="noopener noreferrer" aria-label="YouTube Colegio Andino"
                                    class="w-12 h-12 rounded-xl bg-[#ae0c26] text-white flex items-center justify-center hover:bg-[#1a1d56] hover:scale-105 transition-all duration-300 shadow-md">
                                    <svg class="size-6 fill-current" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.016 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                                    </svg>
                                </a>

                                <!-- Instagram -->
                                <a href="https://www.instagram.com/colegioandinohyo/" target="_blank"
                                    rel="noopener noreferrer" aria-label="Instagram Colegio Andino"
                                    class="w-12 h-12 rounded-xl bg-[#ae0c26] text-white flex items-center justify-center hover:bg-[#1a1d56] hover:scale-105 transition-all duration-300 shadow-md">
                                    <svg class="size-6 stroke-current fill-none stroke-2" viewBox="0 0 24 24"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect width="20" height="20" x="2" y="2" rx="5" ry="5" />
                                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
                                        <line x1="17.5" x2="17.51" y1="6.5" y2="6.5" />
                                    </svg>
                                </a>

                                <!-- TikTok -->
                                <a href="https://www.tiktok.com/@colegioandinohyo" target="_blank"
                                    rel="noopener noreferrer" aria-label="TikTok Colegio Andino"
                                    class="w-12 h-12 rounded-xl bg-[#ae0c26] text-white flex items-center justify-center hover:bg-[#1a1d56] hover:scale-105 transition-all duration-300 shadow-md">
                                    <svg class="size-5 fill-current" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.83 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 3 15.68a6.34 6.33 0 0 0 10.86 4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.05z" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Columna Derecha - Formulario de Contacto -->
                    <div class="lg:col-span-7" data-reveal>
                        <div class="bg-[#1a1d56] rounded-3xl p-8 sm:p-10 md:p-12 shadow-2xl text-white">
                            <h3 class="font-display text-3xl md:text-4xl font-black mb-3">
                                Enviar mensaje
                            </h3>
                            <p class="text-neutral-200 text-base md:text-lg mb-2">
                                Queremos conversar contigo, déjanos tus datos y te contactaremos.
                            </p>
                            <p
                                class="text-[#ae0c26] bg-white/10 inline-block px-3 py-1 rounded-lg font-bold text-xs uppercase tracking-wider mb-8">
                                Datos del padre de familia
                            </p>

                            <form @submit.prevent="submitForm" class="space-y-5">
                                <div>
                                    <label
                                        class="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-2">
                                        Nombres y Apellidos *
                                    </label>
                                    <input v-model="form.name" type="text" required placeholder="Ej. Juan Pérez"
                                        class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3.5 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-[#ae0c26] focus:bg-white/15 transition-all text-base" />
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label
                                            class="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-2">
                                            Correo Electrónico *
                                        </label>
                                        <input v-model="form.email" type="email" required
                                            placeholder="correo@ejemplo.com"
                                            class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3.5 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-[#ae0c26] focus:bg-white/15 transition-all text-base" />
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-2">
                                            Celular - Teléfono *
                                        </label>
                                        <input v-model="form.phone" type="tel" required placeholder="999 999 999"
                                            class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3.5 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-[#ae0c26] focus:bg-white/15 transition-all text-base" />
                                    </div>
                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-2">
                                        Asunto *
                                    </label>
                                    <input v-model="form.subject" type="text" required
                                        placeholder="Informes de admisión, pensiones, etc."
                                        class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3.5 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-[#ae0c26] focus:bg-white/15 transition-all text-base" />
                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-bold uppercase tracking-wider text-neutral-300 mb-2">
                                        Mensaje *
                                    </label>
                                    <textarea v-model="form.message" rows="4" required
                                        placeholder="Escribe tu consulta aquí..."
                                        class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3.5 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-[#ae0c26] focus:bg-white/15 transition-all text-base resize-none"></textarea>
                                </div>

                                <button type="submit"
                                    class="w-full bg-[#ae0c26] hover:bg-[#c40e2e] text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white text-lg mt-4">
                                    <Send class="size-5" />
                                    <span>Enviar mensaje</span>
                                </button>
                            </form>
                        </div>
                    </div>

                </div>

                <!-- Segunda Fila: Mapa Google Embed -->
                <div class="rounded-3xl overflow-hidden shadow-xl border border-neutral-200" data-reveal>
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3861.073346410234!2d-75.2118060208946!3d-12.06303565768519!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x910e964a27f0db9b%3A0xcbcc73d8b09bc8ca!2sColegio%20Andino!5e1!3m2!1ses-419!2spe!4v1766950625103!5m2!1ses-419!2spe"
                        class="w-full h-[400px] md:h-[480px] border-0" allowfullscreen loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>

            </div>
        </section>

        <!-- Modal de Confirmación Provisorio -->
        <div v-if="showModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
            <div class="bg-white rounded-3xl max-w-md w-full p-8 text-center shadow-2xl space-y-4">
                <div
                    class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto">
                    <CheckCircle2 class="size-10" />
                </div>
                <h3 class="font-display text-2xl font-black text-[#1a1d56]">¡Mensaje registrado!</h3>
                <p class="text-neutral-600 text-base leading-relaxed">
                    Tu mensaje fue recibido. Nos pondremos en contacto contigo lo antes posible.
                </p>
                <button @click="showModal = false"
                    class="w-full bg-[#1a1d56] hover:bg-[#ae0c26] text-white font-bold py-3 rounded-xl transition-colors">
                    Entendido
                </button>
            </div>
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