<script setup lang="ts">
import { computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { FileText, CheckCircle2, Download, X } from '@lucide/vue';

interface Empleado {
    id: number;
    first_name: string;
    paternal_surname: string;
    maternal_surname: string;
    dni: string;
}

interface Boleta {
    id: number;
    user_id: number;
    type: 'Boleta Mensual' | 'Gratificación' | 'CTS' | 'Otro';
    mes: string;
    anio: number;
    pdf_path: string;
    status: 'Procesada' | 'Vista' | 'Firmada' | 'Observada';
    created_at: string;
    signed_at: string | null;
    observation_message: string | null;
    user?: Empleado;
}

const props = defineProps<{
    boleta: Boleta;
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

// Formateador semántico de marcas de tiempo peruanas
const formatTimestamp = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('es-PE', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

// Computamos la ruta de lectura del PDF usando el visor nativo de Laravel Storage
const pdfUrl = computed(() => `/boletas/${props.boleta.id}/pdf`);
</script>

<template>
    <Head :title="`${boleta.type} - ${boleta.mes} ${boleta.anio}`" />

    <div class="flex flex-col xl:flex-row gap-6 p-6 h-full w-full max-w-[1600px] mx-auto items-stretch">
        
        <div class="w-full xl:w-[400px] shrink-0 flex flex-col gap-4">
            
            <div class="rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-6 flex flex-col justify-between h-full shadow-sm space-y-6">
                
                <div class="space-y-1">
                    <div class="flex items-center justify-between">
                        <span :class="['px-2.5 py-0.5 text-[10px] font-bold tracking-wider uppercase rounded-md border', 
                            boleta.status === 'Firmada' ? 'bg-green-50 text-green-700 border-green-200 dark:bg-green-950/30 dark:text-green-400 dark:border-green-900/50' : 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/30 dark:text-amber-400 dark:border-amber-900/50'
                        ]">
                            {{ boleta.status }}
                        </span>
                        <span class="text-xs font-mono text-neutral-400">ID: #{{ boleta.id }}</span>
                    </div>
                    <h2 class="text-xl font-black text-neutral-900 dark:text-neutral-50 tracking-tight pt-2">{{ boleta.type }}</h2>
                    <p class="text-xs text-neutral-400 font-medium">{{ boleta.mes }} - {{ boleta.anio }}</p>
                </div>

                <div class="relative pl-6 space-y-6 border-l-2 border-neutral-100 dark:border-neutral-800 ml-3">
                    
                    <div class="relative">
                        <div class="absolute -left-[31px] top-0 p-1 bg-white dark:bg-neutral-900 rounded-full">
                            <CheckCircle2 class="size-4 text-blue-500 bg-white dark:bg-neutral-900 rounded-full" />
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-neutral-800 dark:text-neutral-200">Notificada / Cargada</span>
                            <span class="text-[10px] font-mono text-neutral-400">{{ formatTimestamp(boleta.created_at) }}</span>
                        </div>
                    </div>

                    <div class="relative">
                        <div class="absolute -left-[31px] top-0 p-1 bg-white dark:bg-neutral-900 rounded-full">
                            <CheckCircle2 :class="['size-4 rounded-full bg-white dark:bg-neutral-900', boleta.status !== 'Procesada' ? 'text-blue-500' : 'text-neutral-200 dark:text-neutral-800']" />
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-neutral-800 dark:text-neutral-200">Vista en Plataforma</span>
                            <span class="text-[10px] font-mono text-neutral-400">
                                {{ boleta.status !== 'Procesada' ? 'Verificado por el personal' : 'Pendiente de lectura' }}
                            </span>
                        </div>
                    </div>

                    <div class="relative">
                        <div class="absolute -left-[31px] top-0 p-1 bg-white dark:bg-neutral-900 rounded-full">
                            <CheckCircle2 :class="['size-4 rounded-full bg-white dark:bg-neutral-900', boleta.status === 'Firmada' ? 'text-green-500' : boleta.status === 'Observada' ? 'text-red-500' : 'text-neutral-200 dark:text-neutral-800']" />
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-neutral-800 dark:text-neutral-200">Conformidad Firmada</span>
                            <span class="text-[10px] font-mono text-neutral-400">
                                {{ boleta.signed_at ? formatTimestamp(boleta.signed_at) : 'Esperando firma digital' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="border border-dashed border-neutral-200 dark:border-neutral-800 rounded-xl p-4 bg-neutral-50/50 dark:bg-neutral-950/40 flex flex-col items-center justify-center min-h-[120px] relative overflow-hidden">
                    <template v-if="boleta.status === 'Firmada' && boleta.user">
                        <img v-if="(page.props.auth as any).user.signature_path" :src="(page.props.auth as any).user.signature_path" alt="Firma Electrónica" class="h-16 object-contain dark:invert max-w-full" />
                        <span v-else class="text-[11px] font-mono font-bold uppercase text-green-600 tracking-wider">Firmado Digitalmente</span>
                        <div class="absolute bottom-1 right-2 text-[8px] font-mono text-neutral-400">Huella: {{ boleta.user.dni }}</div>
                    </template>
                    <template v-else-if="boleta.status === 'Observada'">
                        <span class="text-xs text-red-500 font-bold italic text-center">"{{ boleta.observation_message }}"</span>
                    </template>
                    <template v-else>
                        <span class="text-xs italic text-neutral-400 select-none">Documento sin visar</span>
                    </template>
                </div>

                <div class="border-t pt-4 space-y-2">
                    <span class="text-[9px] font-bold uppercase tracking-wider text-neutral-400">Asignación Laboral</span>
                    <div class="flex flex-col" v-if="boleta.user">
                        <span class="text-xs font-bold text-neutral-800 dark:text-neutral-200">{{ boleta.user.paternal_surname }} {{ boleta.user.maternal_surname }}, {{ boleta.user.first_name }}</span>
                        <span class="text-[11px] font-mono text-neutral-400 mt-0.5">DNI: {{ boleta.user.dni }}</span>
                    </div>
                </div>

                <div class="border-t pt-4 flex flex-col gap-2">
                    <a :href="`/boletas/${boleta.id}/descargar`" class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-4 py-2.5 text-xs font-bold text-neutral-700 dark:text-neutral-300 shadow-2xs hover:bg-neutral-50 dark:hover:bg-neutral-700/50 transition-colors">
                        <Download class="size-3.5" /> Descargar PDF Oficial
                    </a>
                    <Link href="/boletas" class="w-full inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2 text-xs font-medium text-neutral-400 hover:text-neutral-900 dark:hover:text-neutral-200 text-center">
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
                <Link href="/boletas" class="text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-200">
                    <X class="size-4" />
                </Link>
            </div>

            <iframe 
                :src="`${pdfUrl}#toolbar=1&navpanes=0`" 
                class="w-full h-full flex-1 border-none bg-neutral-100 dark:bg-neutral-950"
                title="Visor de Boletas Colegio Andino"
            ></iframe>
        </div>

    </div>
</template>