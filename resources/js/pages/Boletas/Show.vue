<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { FileText, Download, X, PenTool, AlertTriangle, CheckCircle2 } from '@lucide/vue';
import SignatureCanvas from '@/components/SignatureCanvas.vue'; 
import ObservationModal from '@/components/ObservationModal.vue'; 

interface EmpleadoPayload {
    nombre_completo: string;
    dni: string;
    cargo: string | null;
}

interface BoletaPayload {
    id: number;
    type: 'Boleta Mensual' | 'Gratificación' | 'CTS' | 'Otro';
    mes: string;
    anio: number;
    status: 'Procesada' | 'Vista' | 'Firmada' | 'Observada';
    created_at: string;        
    read_at: string | null;     
    signed_at: string | null;
    observation_message: string | null;
    is_xml: boolean;
    pdf_url: string;
    puede_firmar: boolean; 
    signature_path_usuario: boolean; 
    empleado: EmpleadoPayload;
}

const props = defineProps<{
    boleta: BoletaPayload;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Documentos Laborales', href: '/boletas' },
            { title: 'Visor de Planilla', href: '#' },
        ],
    },
});

const page = usePage();
const authUser = computed(() => (page.props.auth as any).user);

const showSignatureModal = ref(false);
const showObservationModal = ref(false);

// 🎯 HASH DE RENDERIZADO REACTIVO PARA EL IFRAME
// Cambiará automáticamente cuando el estado mute, forzando la recarga del documento físico
const iframeKey = computed(() => `${props.boleta.status}_${props.boleta.signed_at ?? 'none'}`);

const formatTimestamp = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('es-PE', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
    });
};

const pdfUrl = computed(() => `/boletas/${props.boleta.id}/pdf`);

const handleSignAction = () => {
    if (props.boleta.signature_path_usuario) {
        if (confirm('¿Declaras tu absoluta conformidad con los montos y conceptos expuestos en esta boleta y deseas estampar tu firma digital?')) {
            router.post(`/boletas/${props.boleta.id}/firmar`, {}, {
                preserveScroll: true,
                onSuccess: () => {
                    // Sincronizamos las propiedades locales tras la mutación directa en base registrada
                    props.boleta.status = 'Firmada';
                    props.boleta.puede_firmar = false;
                    props.boleta.signed_at = new Date().toISOString();
                }
            });
        }
    } else {
        showSignatureModal.value = true;
    }
};

const submitSignatureAndSign = (base64Data: string) => {
    router.post(`/boletas/${props.boleta.id}/firmar`, {
        signature_base64: base64Data
    }, {
        preserveScroll: true,
        onSuccess: () => {
            showSignatureModal.value = false;
            props.boleta.status = 'Firmada';
            props.boleta.puede_firmar = false;
            props.boleta.signature_path_usuario = true;
            // Estampamos el timestamp local. Esto altera de inmediato la iframeKey y refresca el PDF
            props.boleta.signed_at = new Date().toISOString();
        }
    });
};

const handleObservationSuccess = () => {
    showObservationModal.value = false;
    props.boleta.status = 'Observada';
};
</script>

<template>
    <Head :title="`${boleta.type} - ${boleta.mes} ${boleta.anio}`" />

    <div class="flex flex-col xl:flex-row gap-6 p-6 h-full w-full max-w-[1600px] mx-auto items-stretch">

        <div class="w-full xl:w-[400px] shrink-0 flex flex-col gap-4">
            <div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-6 flex flex-col justify-between h-full shadow-xs space-y-6">

                <div class="space-y-1">
                    <div class="flex items-center justify-between">
                        <span :class="['px-2.5 py-0.5 text-[10px] font-bold tracking-wider uppercase rounded-md border',
                            boleta.status === 'Firmada' ? 'bg-green-50 text-green-700 border-green-200 dark:bg-green-950/30 dark:text-green-400 dark:border-green-900/50' :
                            boleta.status === 'Observada' ? 'bg-red-50 text-red-700 border-red-200 dark:bg-red-950/30 dark:text-red-400 dark:border-red-900/50' :
                            'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/30 dark:text-amber-400 dark:border-amber-900/50'
                        ]">
                            {{ boleta.status }}
                        </span>
                        <span class="text-xs font-mono text-neutral-400">ID: #{{ boleta.id }}</span>
                    </div>
                    <h2 class="text-xl font-black text-neutral-900 dark:text-neutral-50 tracking-tight pt-2">
                        {{ boleta.type }}
                    </h2>
                    <p class="text-xs text-neutral-400 font-medium">{{ boleta.mes }} - {{ boleta.anio }}</p>
                </div>

                <div class="relative pl-6 space-y-6 border-l-2 border-neutral-100 dark:border-neutral-800 ml-3">
                    <div class="relative">
                        <div class="absolute -left-[31px] top-0 p-1 bg-white dark:bg-neutral-900 rounded-full">
                            <CheckCircle2 class="size-4 text-blue-500 bg-white dark:bg-neutral-900 rounded-full" />
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-neutral-800 dark:text-neutral-200">Notificada / Cargada</span>
                            <span class="text-[10px] font-mono text-neutral-400 mt-0.5">
                                {{ boleta.created_at ? formatTimestamp(boleta.created_at) : 'Sin registro' }}
                            </span>
                        </div>
                    </div>

                    <div class="relative">
                        <div class="absolute -left-[31px] top-0 p-1 bg-white dark:bg-neutral-900 rounded-full">
                            <CheckCircle2 :class="['size-4 rounded-full bg-white dark:bg-neutral-900', boleta.status !== 'Procesada' ? 'text-blue-500' : 'text-neutral-200 dark:text-neutral-800']" />
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-neutral-800 dark:text-neutral-200">Vista en Plataforma</span>
                            <span class="text-[10px] font-mono text-neutral-400 mt-0.5">
                                {{ boleta.read_at ? formatTimestamp(boleta.read_at) : 'Pendiente de lectura' }}
                            </span>
                        </div>
                    </div>

                    <div class="relative">
                        <div class="absolute -left-[31px] top-0 p-1 bg-white dark:bg-neutral-900 rounded-full">
                            <CheckCircle2 :class="['size-4 rounded-full bg-white dark:bg-neutral-900', boleta.status === 'Firmada' ? 'text-green-500' : boleta.status === 'Observada' ? 'text-red-500' : 'text-neutral-200 dark:text-neutral-800']" />
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-neutral-800 dark:text-neutral-200">Conformidad Firmada</span>
                            <span class="text-[10px] font-mono text-neutral-400 mt-0.5">
                                {{ boleta.signed_at ? formatTimestamp(boleta.signed_at) : 'Esperando visado digital' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="border border-dashed border-neutral-200 dark:border-neutral-800 rounded-xl p-4 bg-neutral-50/50 dark:bg-neutral-950/40 flex flex-col items-center justify-center min-h-[110px] relative overflow-hidden">
                    <template v-if="boleta.status === 'Firmada'">
                        <div class="bg-white p-2 rounded-lg shadow-inner flex items-center justify-center border border-neutral-200">
                            <img :src="`/boletas/${boleta.id}/trabajador-firma`" alt="Firma Electrónica Autorizada" class="h-14 object-contain max-w-full block" />
                        </div>
                        <div class="absolute bottom-1 right-2 text-[8px] font-mono text-neutral-400">Huella: {{ boleta.empleado.dni }}</div>
                    </template>
                    <template v-else-if="boleta.status === 'Observada'">
                        <span class="text-[10px] font-bold text-red-500 uppercase flex items-center gap-1 mb-1">
                            <AlertTriangle class="size-3" /> Sustento del Reclamo:
                        </span>
                        <span class="text-xs text-neutral-600 dark:text-neutral-400 italic text-center px-1">
                            "{{ boleta.observation_message }}"
                        </span>
                    </template>
                    <template v-else>
                        <span class="text-xs italic text-neutral-400 select-none">Documento sin visar</span>
                    </template>
                </div>

                <div class="border-t pt-4 space-y-2">
                    <span class="text-[9px] font-bold uppercase tracking-wider text-neutral-400">Asignación Laboral</span>
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-neutral-800 dark:text-neutral-200">
                            {{ boleta.empleado.nombre_completo }}
                        </span>
                        <span class="text-[11px] font-mono text-neutral-400 mt-0.5">DNI: {{ boleta.empleado.dni }}</span>
                    </div>
                </div>

                <div class="border-t pt-4 flex flex-col gap-2">
                    <a :href="`/boletas/${boleta.id}/descargar`" class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-4 py-2.5 text-xs font-bold text-neutral-700 dark:text-neutral-300 shadow-2xs hover:bg-neutral-50 dark:hover:bg-neutral-700/50 transition-colors">
                        <Download class="size-3.5" /> Descargar PDF Oficial
                    </a>

                    <template v-if="boleta.puede_firmar">
                        <button type="button" @click="handleSignAction" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-green-600 text-white px-4 py-2.5 text-xs font-bold shadow-sm hover:bg-green-500 transition-colors">
                            <PenTool class="size-3.5" /> Firmar Conformidad Digital
                        </button>
                        <button type="button" @click="showObservationModal = true" class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-red-200 dark:border-red-900/40 bg-red-50/50 dark:bg-red-950/20 px-4 py-2 text-xs font-bold text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/40 transition-colors">
                            Observar / Solicitar Revisión
                        </button>
                    </template>

                    <Link :href="authUser && (authUser.roles.includes('Administrador') || authUser.roles.includes('Recursos Humanos')) ? '/boletas' : '/mis-boletas'" class="w-full inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2 text-xs font-medium text-neutral-400 hover:text-neutral-900 dark:hover:text-neutral-200 text-center mt-1">
                        Regresar al Historial
                    </Link>
                </div>
            </div>
        </div>

        <div class="flex-1 rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-neutral-100 dark:bg-neutral-950 overflow-hidden shadow-sm flex flex-col min-h-[500px] lg:min-h-0">
            <div class="bg-white dark:bg-neutral-900 border-b border-neutral-200 dark:border-neutral-800 px-4 py-3 flex justify-between items-center shrink-0">
                <div class="flex items-center gap-2">
                    <FileText class="size-4 text-blue-500" />
                    <span class="text-xs font-mono font-bold text-neutral-700 dark:text-neutral-300 truncate max-w-md">
                        {{ boleta.type }} — {{ boleta.mes }} {{ boleta.anio }}
                    </span>
                </div>
                <Link :href="authUser && (authUser.roles.includes('Administrador') || authUser.roles.includes('Recursos Humanos')) ? '/boletas' : '/mis-boletas'" class="text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-200">
                    <X class="size-4" />
                </Link>
            </div>

            <iframe 
                :key="iframeKey"
                :src="`${pdfUrl}#toolbar=1&navpanes=0`" 
                class="w-full h-full flex-1 border-none bg-neutral-100 dark:bg-neutral-950" 
                title="Visor de Boletas Colegio Andino"
            ></iframe>
        </div>

        <div v-if="showSignatureModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs p-4">
            <div class="w-full max-w-lg shadow-xl bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-2xl p-6">
                <div class="space-y-0.5 mb-4">
                    <h3 class="text-base font-bold text-neutral-900 dark:text-neutral-50 flex items-center gap-2 uppercase tracking-tight">
                        <PenTool class="size-4 text-amber-500" /> Registrar Firma Digital
                    </h3>
                    <p class="text-xs text-neutral-400">Plasma tu trazo dentro del recuadro para archivarlo en tu cuenta y proceder con el visado.</p>
                </div>
                <SignatureCanvas @save="submitSignatureAndSign" @cancel="showSignatureModal = false" />
            </div>
        </div>

        <ObservationModal :open="showObservationModal" :boleta-id="boleta.id" @close="showObservationModal = false" @success="handleObservationSuccess" />

    </div>
</template>