<script setup lang="ts">
import WebLayout from '@/layouts/web/WebLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref, onMounted, nextTick } from 'vue';
import { ShieldCheck, CheckCircle2, Send, Copy, Check } from '@lucide/vue';

const props = defineProps<{
    title?: string;
    recaptchaSiteKey?: string;
}>();

const sectionRef = ref<HTMLElement | null>(null);
const showSuccessModal = ref(false);
const correlativoGenerado = ref('');
const copied = ref(false);
const recaptchaWidgetId = ref<number | null>(null);

const siteKey = props.recaptchaSiteKey || '6LfxHY8sAAAAAImaWTupD1U31VXayYU0UcrfN24s';
const page = usePage();

const form = useForm({
    nombres: '',
    apellidos: '',
    correo: '',
    domicilio: '',
    dni: '',
    telefono: '',
    padre_madre: '',
    bien_contratado: 'servicio',
    descripcion_bien: '',
    tipo_reclamo: 'reclamo',
    detalle_reclamo: '',
    politica: false,
    recaptcha_token: '',
});

// Inicializar Google reCAPTCHA
const initRecaptcha = () => {
    if (typeof window !== 'undefined' && (window as any).grecaptcha) {
        (window as any).grecaptcha.ready(() => {
            const container = document.getElementById('recaptcha-container');
            if (container && container.innerHTML === '') {
                recaptchaWidgetId.value = (window as any).grecaptcha.render('recaptcha-container', {
                    sitekey: siteKey,
                    callback: (token: string) => {
                        form.recaptcha_token = token;
                    },
                    'expired-callback': () => {
                        form.recaptcha_token = '';
                    },
                });
            }
        });
    }
};

const resetRecaptcha = () => {
    if (recaptchaWidgetId.value !== null && (window as any).grecaptcha) {
        (window as any).grecaptcha.reset(recaptchaWidgetId.value);
        form.recaptcha_token = '';
    }
};

const copyCorrelativo = async () => {
    if (!correlativoGenerado.value) return;
    try {
        await navigator.clipboard.writeText(correlativoGenerado.value);
        copied.value = true;
        setTimeout(() => (copied.value = false), 2500);
    } catch {
        // Fallback en caso de bloqueo de clipboard
    }
};

const submitReclamo = () => {
    if (!form.recaptcha_token) {
        form.errors.recaptcha_token = 'Por favor marque la casilla de verificación reCAPTCHA.';
        return;
    }

    form.post('/libro-de-reclamaciones', {
        preserveScroll: true,
        onSuccess: (pageResponse: any) => {
            // Extrae el correlativo desde el payload retornado por Inertia
            const flash = pageResponse.props.flash;
            const correlativo = flash?.correlativo || pageResponse.props.correlativo;

            if (correlativo) {
                correlativoGenerado.value = correlativo;
            }

            showSuccessModal.value = true;
            form.reset();
            resetRecaptcha();
        },
        onError: () => {
            resetRecaptcha();
        }
    });
};
// Limitar y sanitizar entrada solo a dígitos numéricos
const filterNumbers = (event: Event, field: 'dni' | 'telefono', maxLength: number) => {
    const input = event.target as HTMLInputElement;
    const cleanValue = input.value.replace(/\D/g, '').slice(0, maxLength);
    form[field] = cleanValue;
    input.value = cleanValue;
};

// Limitar nombres y apellidos solo a letras y espacios
const filterLetters = (event: Event, field: 'nombres' | 'apellidos' | 'padre_madre') => {
    const input = event.target as HTMLInputElement;
    const cleanValue = input.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
    form[field] = cleanValue;
    input.value = cleanValue;
};

onMounted(() => {
    // Carga diferida del script de Google reCAPTCHA
    if (!document.getElementById('recaptcha-script')) {
        const script = document.createElement('script');
        script.id = 'recaptcha-script';
        script.src = 'https://www.google.com/recaptcha/api.js?render=explicit';
        script.async = true;
        script.defer = true;
        script.onload = () => initRecaptcha();
        document.head.appendChild(script);
    } else {
        nextTick(() => initRecaptcha());
    }

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

    <Head :title="title || 'Libro de Reclamaciones - Colegio Andino'" />

    <WebLayout>
        <!-- Banner Principal -->
        <section class="relative min-h-[460px] md:min-h-[540px] flex items-center bg-[#1a1d56] overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-[#ae0c26] z-30"></div>

            <div class="absolute inset-0 z-0">
                <img src="/img/nosotros/seccion.jpg" alt="Libro de Reclamaciones Colegio Andino"
                    class="w-full h-full object-cover object-center" />
                <div class="absolute inset-0 bg-[#1a1d56]/75"></div>
            </div>

            <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center py-20 w-full">
                <h1
                    class="font-display text-4xl sm:text-6xl lg:text-7xl font-black text-white leading-tight drop-shadow-lg uppercase">
                    LIBRO DE<br />RECLAMACIONES
                </h1>
                <div class="w-24 h-1.5 bg-[#ae0c26] mx-auto rounded-full mt-6"></div>
            </div>
        </section>

        <!-- Formulario -->
        <section ref="sectionRef" class="py-16 md:py-24 bg-neutral-50 font-sans">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-3xl p-6 sm:p-10 md:p-12 shadow-xl border border-neutral-200/80"
                    data-reveal>

                    <!-- Aviso Legal INDECOPI -->
                    <div
                        class="flex items-start gap-3.5 p-4 rounded-2xl bg-amber-50 border border-amber-200/80 mb-8 text-amber-900 text-xs sm:text-sm leading-relaxed">
                        <ShieldCheck class="size-6 text-amber-600 shrink-0 mt-0.5" />
                        <div>
                            <strong>Hoja de Reclamación:</strong> Conforme al D.S. N° 011-2011-PCM / INDECOPI. El
                            proveedor deberá dar respuesta al reclamo en un plazo no mayor a <strong>quince (15) días
                                hábiles</strong>.
                        </div>
                    </div>

                    <form @submit.prevent="submitReclamo" class="space-y-10">

                        <!-- 1. Identificación del consumidor -->
                        <!-- 1. Identificación del consumidor -->
                        <div class="space-y-4">
                            <div class="border-b border-neutral-200 pb-3">
                                <h2
                                    class="font-display text-lg sm:text-xl font-black text-[#1a1d56] uppercase tracking-wide">
                                    1. Identificación del consumidor reclamante
                                </h2>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Nombres -->
                                <div>
                                    <label class="block text-xs font-bold uppercase text-neutral-600 mb-1.5">Nombres
                                        <span class="text-[#ae0c26]">*</span></label>
                                    <input :value="form.nombres" @input="filterLetters($event, 'nombres')" type="text"
                                        maxlength="100" required placeholder="Nombres"
                                        class="w-full bg-[#f5f5f5] border border-neutral-200 rounded-xl px-4 py-2.5 text-neutral-800 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#ae0c26] transition-all" />
                                    <span v-if="form.errors.nombres"
                                        class="text-xs text-[#ae0c26] mt-1 block font-semibold">{{ form.errors.nombres
                                        }}</span>
                                </div>

                                <!-- Apellidos -->
                                <div>
                                    <label class="block text-xs font-bold uppercase text-neutral-600 mb-1.5">Apellidos
                                        <span class="text-[#ae0c26]">*</span></label>
                                    <input :value="form.apellidos" @input="filterLetters($event, 'apellidos')"
                                        type="text" maxlength="100" required placeholder="Apellidos"
                                        class="w-full bg-[#f5f5f5] border border-neutral-200 rounded-xl px-4 py-2.5 text-neutral-800 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#ae0c26] transition-all" />
                                    <span v-if="form.errors.apellidos"
                                        class="text-xs text-[#ae0c26] mt-1 block font-semibold">{{ form.errors.apellidos
                                        }}</span>
                                </div>

                                <!-- Correo Electrónico -->
                                <div>
                                    <label class="block text-xs font-bold uppercase text-neutral-600 mb-1.5">Correo
                                        Electrónico <span class="text-[#ae0c26]">*</span></label>
                                    <input v-model="form.correo" type="email" required placeholder="correo@ejemplo.com"
                                        class="w-full bg-[#f5f5f5] border border-neutral-200 rounded-xl px-4 py-2.5 text-neutral-800 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#ae0c26] transition-all" />
                                    <span v-if="form.errors.correo"
                                        class="text-xs text-[#ae0c26] mt-1 block font-semibold">{{ form.errors.correo
                                        }}</span>
                                </div>

                                <!-- Domicilio -->
                                <div>
                                    <label class="block text-xs font-bold uppercase text-neutral-600 mb-1.5">Domicilio
                                        <span class="text-[#ae0c26]">*</span></label>
                                    <input v-model="form.domicilio" type="text" required placeholder="Domicilio"
                                        class="w-full bg-[#f5f5f5] border border-neutral-200 rounded-xl px-4 py-2.5 text-neutral-800 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#ae0c26] transition-all" />
                                    <span v-if="form.errors.domicilio"
                                        class="text-xs text-[#ae0c26] mt-1 block font-semibold">{{ form.errors.domicilio
                                        }}</span>
                                </div>

                                <!-- DNI / Carné de Extranjería (Solo números, 8 a 12 dígitos) -->
                                <div>
                                    <label class="block text-xs font-bold uppercase text-neutral-600 mb-1.5">DNI / Carné
                                        de Extranjería <span class="text-[#ae0c26]">*</span></label>
                                    <input :value="form.dni" @input="filterNumbers($event, 'dni', 12)" type="text"
                                        inputmode="numeric" pattern="[0-9]{8,12}" maxlength="12" required
                                        placeholder="Ej. 74859632"
                                        class="w-full bg-[#f5f5f5] border border-neutral-200 rounded-xl px-4 py-2.5 text-neutral-800 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#ae0c26] transition-all font-mono" />
                                    <span v-if="form.errors.dni"
                                        class="text-xs text-[#ae0c26] mt-1 block font-semibold">{{ form.errors.dni
                                        }}</span>
                                </div>

                                <!-- Teléfono / Celular (Solo números, 7 a 9 dígitos) -->
                                <div>
                                    <label class="block text-xs font-bold uppercase text-neutral-600 mb-1.5">Teléfono /
                                        Celular <span class="text-[#ae0c26]">*</span></label>
                                    <input :value="form.telefono" @input="filterNumbers($event, 'telefono', 9)"
                                        type="tel" inputmode="numeric" pattern="[0-9]{7,9}" maxlength="9" required
                                        placeholder="Ej. 944688081 o 064238521"
                                        class="w-full bg-[#f5f5f5] border border-neutral-200 rounded-xl px-4 py-2.5 text-neutral-800 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#ae0c26] transition-all font-mono" />
                                    <span v-if="form.errors.telefono"
                                        class="text-xs text-[#ae0c26] mt-1 block font-semibold">{{ form.errors.telefono
                                        }}</span>
                                </div>

                                <!-- Nombre Apoderado -->
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-bold uppercase text-neutral-600 mb-1.5">Nombre de
                                        Padre o Madre (para el caso de menores de edad)</label>
                                    <input :value="form.padre_madre" @input="filterLetters($event, 'padre_madre')"
                                        type="text" maxlength="150" placeholder="Nombre completo del apoderado"
                                        class="w-full bg-[#f5f5f5] border border-neutral-200 rounded-xl px-4 py-2.5 text-neutral-800 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#ae0c26] transition-all" />
                                    <span v-if="form.errors.padre_madre"
                                        class="text-xs text-[#ae0c26] mt-1 block font-semibold">{{
                                        form.errors.padre_madre }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Identificación del bien contratado -->
                        <div class="space-y-4">
                            <div class="border-b border-neutral-200 pb-3">
                                <h2
                                    class="font-display text-lg sm:text-xl font-black text-[#1a1d56] uppercase tracking-wide">
                                    2. Identificación del bien contratado
                                </h2>
                            </div>

                            <div class="flex items-center gap-6">
                                <label
                                    class="flex items-center gap-2 cursor-pointer text-sm font-semibold text-neutral-700">
                                    <input type="radio" v-model="form.bien_contratado" value="servicio"
                                        class="size-4 text-[#ae0c26] focus:ring-[#ae0c26]" />
                                    <span>Servicio</span>
                                </label>
                                <label
                                    class="flex items-center gap-2 cursor-pointer text-sm font-semibold text-neutral-700">
                                    <input type="radio" v-model="form.bien_contratado" value="producto"
                                        class="size-4 text-[#ae0c26] focus:ring-[#ae0c26]" />
                                    <span>Producto</span>
                                </label>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase text-neutral-600 mb-1.5">Descripción
                                    <span class="text-[#ae0c26]">*</span></label>
                                <textarea v-model="form.descripcion_bien" rows="3" required
                                    placeholder="¿Cuál fue el servicio / producto y en qué área u oficina?"
                                    class="w-full bg-[#f5f5f5] border border-neutral-200 rounded-xl px-4 py-2.5 text-neutral-800 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#ae0c26] transition-all resize-none"></textarea>
                                <span v-if="form.errors.descripcion_bien"
                                    class="text-xs text-[#ae0c26] mt-1 block font-semibold">{{
                                    form.errors.descripcion_bien }}</span>
                            </div>
                        </div>

                        <!-- 3. Detalle de la reclamación -->
                        <div class="space-y-4">
                            <div class="border-b border-neutral-200 pb-3">
                                <h2
                                    class="font-display text-lg sm:text-xl font-black text-[#1a1d56] uppercase tracking-wide">
                                    3. Detalle de la reclamación
                                </h2>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="p-4 rounded-2xl border cursor-pointer transition-all"
                                    :class="form.tipo_reclamo === 'reclamo' ? 'border-[#ae0c26] bg-[#ae0c26]/5' : 'border-neutral-200 bg-neutral-50'"
                                    @click="form.tipo_reclamo = 'reclamo'">
                                    <label class="flex items-center gap-2 font-bold text-neutral-800 cursor-pointer">
                                        <input type="radio" v-model="form.tipo_reclamo" value="reclamo"
                                            class="text-[#ae0c26] focus:ring-[#ae0c26]" />
                                        <span>Reclamo</span>
                                    </label>
                                    <p class="text-xs text-neutral-500 mt-1 pl-6">
                                        Disconformidad relacionada a los productos o servicios.
                                    </p>
                                </div>

                                <div class="p-4 rounded-2xl border cursor-pointer transition-all"
                                    :class="form.tipo_reclamo === 'queja' ? 'border-[#ae0c26] bg-[#ae0c26]/5' : 'border-neutral-200 bg-neutral-50'"
                                    @click="form.tipo_reclamo = 'queja'">
                                    <label class="flex items-center gap-2 font-bold text-neutral-800 cursor-pointer">
                                        <input type="radio" v-model="form.tipo_reclamo" value="queja"
                                            class="text-[#ae0c26] focus:ring-[#ae0c26]" />
                                        <span>Queja</span>
                                    </label>
                                    <p class="text-xs text-neutral-500 mt-1 pl-6">
                                        Disconformidad no relacionada a los productos o servicios, o malestar respecto a
                                        la atención al público.
                                    </p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase text-neutral-600 mb-1.5">Descripción del
                                    detalle <span class="text-[#ae0c26]">*</span></label>
                                <textarea v-model="form.detalle_reclamo" rows="4" required
                                    placeholder="Describa el detalle de su reclamación"
                                    class="w-full bg-[#f5f5f5] border border-neutral-200 rounded-xl px-4 py-2.5 text-neutral-800 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#ae0c26] transition-all resize-none"></textarea>
                                <span v-if="form.errors.detalle_reclamo"
                                    class="text-xs text-[#ae0c26] mt-1 block font-semibold">{{
                                    form.errors.detalle_reclamo }}</span>
                            </div>

                            <p class="text-xs text-neutral-500 leading-relaxed">
                                La formulación del reclamo no impide acudir a otras vías de solución de controversias ni
                                es requisito previo para interponer una denuncia ante el INDECOPI. El proveedor deberá
                                dar respuesta al reclamo en un plazo no mayor a quince (15) días hábiles, previa
                                comunicación al consumidor.
                            </p>

                            <!-- Checkbox Política -->
                            <div class="pt-2">
                                <label class="flex items-start gap-2.5 cursor-pointer text-xs text-neutral-700">
                                    <input v-model="form.politica" type="checkbox" required
                                        class="size-4 rounded text-[#ae0c26] focus:ring-[#ae0c26] mt-0.5" />
                                    <span>Acepto las Políticas de privacidad y protección de datos personales. <span
                                            class="text-[#ae0c26]">*</span></span>
                                </label>
                                <span v-if="form.errors.politica"
                                    class="text-xs text-[#ae0c26] block mt-1 font-semibold">{{ form.errors.politica
                                    }}</span>
                            </div>
                        </div>

                        <!-- Google reCAPTCHA Widget -->
                        <div class="space-y-2 pt-2">
                            <div id="recaptcha-container" class="flex justify-start"></div>
                            <span v-if="form.errors.recaptcha_token" class="text-xs text-[#ae0c26] block font-semibold">
                                {{ form.errors.recaptcha_token }}
                            </span>
                        </div>

                        <!-- Botón Enviar -->
                        <div class="pt-4 border-t border-neutral-200 flex justify-start">
                            <button type="submit" :disabled="form.processing"
                                class="bg-[#ae0c26] hover:bg-[#8a0a1e] text-white font-bold py-3.5 px-10 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg flex items-center gap-2 text-sm uppercase tracking-wider disabled:opacity-50">
                                <Send class="size-4" />
                                <span>{{ form.processing ? 'Enviando...' : 'Enviar' }}</span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </section>

        <!-- Modal de Éxito con Copiado y Seguimiento -->
        <div v-if="showSuccessModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
            <div class="bg-white rounded-3xl max-w-md w-full p-8 text-center shadow-2xl space-y-5">
                <div
                    class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto">
                    <CheckCircle2 class="size-10" />
                </div>

                <div>
                    <h3 class="font-display text-2xl font-black text-[#1a1d56]">¡Reclamo Registrado!</h3>
                    <p class="text-neutral-500 text-xs mt-1">Guarda este número para consultar el estado de tu trámite.
                    </p>
                </div>

                <!-- Tarjeta del Código Correlativo con botón Copiar -->
                <div
                    class="bg-neutral-50 border-2 border-dashed border-[#ae0c26]/40 rounded-2xl p-4 flex items-center justify-between gap-3">
                    <div class="text-left">
                        <span class="text-[10px] font-black tracking-widest uppercase text-neutral-400 block">Número de
                            Hoja</span>
                        <span class="text-2xl font-black text-[#ae0c26] tracking-wider font-mono">{{ correlativoGenerado
                            }}</span>
                    </div>
                    <button type="button" @click="copyCorrelativo"
                        class="p-2.5 rounded-xl border border-neutral-200 bg-white hover:bg-neutral-100 text-neutral-700 transition-colors flex items-center gap-1.5 text-xs font-bold"
                        :title="copied ? '¡Copiado!' : 'Copiar al portapapeles'">
                        <component :is="copied ? Check : Copy" class="size-4"
                            :class="copied ? 'text-emerald-600' : 'text-neutral-600'" />
                        <span>{{ copied ? 'Copiado' : 'Copiar' }}</span>
                    </button>
                </div>

                <p class="text-neutral-600 text-xs leading-relaxed text-justify">
                    Se ha generado tu constancia virtual. El colegio atenderá tu solicitud formalmente dentro del plazo
                    legal de <strong>15 días hábiles</strong>.
                </p>

                <button @click="showSuccessModal = false"
                    class="w-full bg-[#ae0c26] hover:bg-[#8a0a1e] text-white font-bold py-3.5 rounded-xl transition-colors text-sm uppercase tracking-wider">
                    Entendido y Cerrar
                </button>
            </div>
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