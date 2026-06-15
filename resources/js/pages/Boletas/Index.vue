<script setup lang="ts">
import { computed, ref } from 'vue';
import { Head, usePage, useForm, Link, router } from '@inertiajs/vue3';

// 1. Tipados estrictos basados en tu esquema de base de datos y modelo de usuarios
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
    signed_at: string | null;
    observation_message: string | null;
    user?: Empleado;
}

interface PaginatedBoletas {
    data: Boleta[];
    links: {
        url: string | null;
        label: string;
        active: boolean;
    }[];
    current_page: number;
    last_page: number;
    total: number;
}

defineProps<{
    boletas: PaginatedBoletas;
    filters: any;
}>();

// 2. Configuración de la barra superior institucional (Breadcrumbs)
defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Documentos Laborales', href: '/boletas' },
        ],
    },
});

// 3. Control de roles perimetrales de la plataforma
const page = usePage();
const authUser = computed(() => (page.props.auth as any).user);
const isAdminOrRRHH = computed(() =>
    authUser.value?.roles?.includes('Administrador') ||
    authUser.value?.roles?.includes('Recursos Humanos')
);

// 4. Estados reactivos para la ventana modal de observaciones
const selectedBoletaForObserve = ref<Boleta | null>(null);
const observeForm = useForm({
    observation_message: '',
});

// 5. Acciones operativas nativas usando la instancia global de 'router' e Inertia Form
const signBoleta = (id: number) => {
    if (confirm('¿Declaras tu conformidad y deseas estampar tu firma digital registrada en este documento?')) {
        router.post(`/boletas/${id}/firmar`);
    }
};

const openObservationModal = (boleta: Boleta) => {
    selectedBoletaForObserve.value = boleta;
    observeForm.observation_message = '';
};

const submitObservation = () => {
    if (selectedBoletaForObserve.value) {
        observeForm.post(`/boletas/${selectedBoletaForObserve.value.id}/observar`, {
            preserveScroll: true,
            onSuccess: () => {
                selectedBoletaForObserve.value = null;
                observeForm.reset();
            }
        });
    }
};

const sendNotification = (id: number) => {
    router.post(`/boletas/${id}/notificar`);
};

const deleteBoleta = (id: number) => {
    if (confirm('¿Está completamente seguro de eliminar este documento de forma permanente? Esta acción no se puede deshacer.')) {
        router.delete(`/boletas/${id}`);
    }
};

// 6. Helpers visuales semánticos de estados y fechas locales
const getStatusBadgeClass = (status: string) => {
    switch (status) {
        case 'Firmada':
            return 'bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-500/10 dark:text-green-400';
        case 'Observada':
            return 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20 dark:bg-red-500/10 dark:text-red-400';
        case 'Vista':
            return 'bg-sky-50 text-sky-700 ring-1 ring-inset ring-sky-600/20 dark:bg-sky-500/10 dark:text-sky-400';
        default:
            return 'bg-neutral-50 text-neutral-600 ring-1 ring-inset ring-neutral-500/10';
    }
};

const getDocTypeBadgeClass = (type: string) => {
    switch (type) {
        case 'CTS':
            return 'bg-amber-50 text-amber-700 ring-amber-700/10 dark:bg-amber-400/10 dark:text-amber-400';
        case 'Gratificación':
            return 'bg-purple-50 text-purple-700 ring-purple-700/10 dark:bg-purple-400/10 dark:text-purple-400';
        default:
            return 'bg-blue-50 text-blue-700 ring-blue-700/10 dark:bg-blue-400/10 dark:text-blue-400';
    }
};

const formatTimestamp = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('es-PE', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};
</script>

<template>
    <Head title="Gestión de Boletas y Documentos" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-6">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100">
                    Documentos Laborales
                </h1>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    Panel centralizado para el control, firma digital y auditoría de boletas de pago y CTS.
                </p>
            </div>
            <Link 
                v-if="isAdminOrRRHH" 
                href="/boletas/create"
                class="inline-flex items-center justify-center rounded-md bg-neutral-900 px-4 py-2 text-sm font-medium text-neutral-50 shadow transition-colors hover:bg-neutral-800 dark:bg-neutral-50 dark:text-neutral-900 dark:hover:bg-neutral-50/90"
            >
                + Inyección Masiva (PLAME/CTS)
            </Link>
        </div>

        <div class="relative flex-1 rounded-xl border border-sidebar-border/70 bg-white p-4 dark:border-sidebar-border dark:bg-neutral-900 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-neutral-500 dark:text-neutral-400">
                    <thead class="text-xs uppercase bg-neutral-50 dark:bg-neutral-800/50 text-neutral-700 dark:text-neutral-300 border-b border-sidebar-border/70">
                        <tr>
                            <th scope="col" class="px-4 py-3 font-semibold text-center">#</th>
                            <th v-if="isAdminOrRRHH" scope="col" class="px-6 py-3 font-semibold">Empleado</th>
                            <th scope="col" class="px-6 py-3 font-semibold">Tipo</th>
                            <th scope="col" class="px-6 py-3 font-semibold">Período</th>
                            <th scope="col" class="px-4 py-3 font-semibold text-center">Estado</th>
                            <th scope="col" class="px-6 py-3 font-semibold">Auditoría / Firma</th>
                            <th scope="col" class="px-6 py-3 font-semibold text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-800">
                        <tr v-if="boletas.data.length === 0">
                            <td :colspan="isAdminOrRRHH ? 7 : 6" class="p-8 text-center text-neutral-400 select-none italic">
                                No se han encontrado boletas de pago cargadas o asignadas en este período.
                            </td>
                        </tr>

                        <tr 
                            v-for="(boleta, index) in boletas.data" 
                            :key="boleta.id" 
                            class="hover:bg-neutral-50/50 dark:hover:bg-neutral-800/30 transition-colors"
                        >
                            <td class="px-4 py-4 text-center font-mono text-neutral-400 text-xs">
                                {{ (boletas.current_page - 1) * 10 + index + 1 }}
                            </td>

                            <td v-if="isAdminOrRRHH" class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col" v-if="boleta.user">
                                    <span class="font-medium text-neutral-900 dark:text-neutral-100">
                                        {{ boleta.user.paternal_surname }} {{ boleta.user.maternal_surname }}, {{ boleta.user.first_name }}
                                    </span>
                                    <span class="text-xs font-mono text-neutral-400">DNI: {{ boleta.user.dni }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="['inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset', getDocTypeBadgeClass(boleta.type)]">
                                    {{ boleta.type }}
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap font-medium text-neutral-700 dark:text-neutral-300">
                                {{ boleta.mes }} - {{ boleta.anio }}
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                <span :class="['inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium ring-1 ring-inset tracking-wider uppercase', getStatusBadgeClass(boleta.status)]">
                                    {{ boleta.status }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-xs text-neutral-600 dark:text-neutral-400">
                                <div v-if="boleta.status === 'Firmada' && boleta.signed_at" class="flex flex-col">
                                    <span class="font-semibold text-green-600 dark:text-green-400">Firmado Conformidad</span>
                                    <span class="text-[11px] font-mono">{{ formatTimestamp(boleta.signed_at) }}</span>
                                </div>
                                <div v-else-if="boleta.status === 'Observada'" class="flex flex-col max-w-xs">
                                    <span class="font-semibold text-red-500">Motivo de Reclamo:</span>
                                    <span class="truncate italic text-[11px]" :title="boleta.observation_message ?? ''">"{{ boleta.observation_message }}"</span>
                                </div>
                                <span v-else class="text-neutral-400 italic">Pendiente de firma</span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                <a :href="`/boletas/${boleta.id}`" target="_blank" class="text-neutral-600 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-neutral-200">
                                    Ver
                                </a>

                                <template v-if="!isAdminOrRRHH && boleta.user_id === authUser.id">
                                    <button v-if="boleta.status !== 'Firmada'" @click="signBoleta(boleta.id)" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 font-bold">
                                        Firmar
                                    </button>
                                    <button v-if="boleta.status !== 'Firmada' && boleta.status !== 'Observada'" @click="openObservationModal(boleta)" class="text-red-500 hover:text-red-700 dark:text-red-400">
                                        Observar
                                    </button>
                                </template>

                                <template v-if="isAdminOrRRHH">
                                    <button v-if="boleta.status !== 'Firmada'" @click="sendNotification(boleta.id)" class="text-sky-600 hover:text-sky-900 dark:text-sky-400">
                                        Notificar
                                    </button>
                                    <button @click="deleteBoleta(boleta.id)" class="text-red-600 hover:text-red-900 dark:text-red-400">
                                        Eliminar
                                    </button>
                                </template>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="boletas.last_page > 1" class="flex items-center justify-between border-t border-neutral-200 dark:border-neutral-800 px-4 py-3 sm:px-6 mt-4">
                <div class="flex flex-1 justify-between sm:hidden">
                    <Link :href="boletas.links[0].url || '#'" :class="['relative inline-flex items-center rounded-md border border-neutral-300 bg-white px-4 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800', !boletas.links[0].url && 'opacity-50 pointer-events-none']">
                        Anterior
                    </Link>
                    <Link :href="boletas.links[boletas.links.length - 1].url || '#'" :class="['relative ml-3 inline-flex items-center rounded-md border border-neutral-300 bg-white px-4 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800', !boletas.links[boletas.links.length - 1].url && 'opacity-50 pointer-events-none']">
                        Siguiente
                    </Link>
                </div>
                <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-neutral-700 dark:text-neutral-400">
                            Mostrando <span class="font-semibold text-neutral-900 dark:text-neutral-100">{{ boletas.current_page }}</span> de <span class="font-semibold text-neutral-900 dark:text-neutral-100">{{ boletas.last_page }}</span> (<span class="text-xs text-neutral-500">{{ boletas.total }} documentos en total</span>)
                        </p>
                    </div>
                    <div>
                        <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                            <Link v-for="(link, key) in boletas.links" :key="key" :href="link.url || '#'" :class="[
                                'relative inline-flex items-center px-3 py-2 text-sm font-semibold focus:z-20',
                                link.active
                                    ? 'z-10 bg-neutral-900 text-white dark:bg-neutral-50 dark:text-neutral-900'
                                    : 'text-neutral-900 ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 dark:text-neutral-300 dark:ring-neutral-700 dark:hover:bg-neutral-800/50',
                                !link.url && 'opacity-40 pointer-events-none',
                                key === 0 && 'rounded-l-md',
                                key === boletas.links.length - 1 && 'rounded-r-md'
                            ]" v-html="link.label" />
                        </nav>
                    </div>
                </div>
            </div>

        </div>

        <div v-if="selectedBoletaForObserve" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs p-4 animate-in fade-in duration-150">
            <div class="w-full max-w-md shadow-xl bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl p-6 animate-in zoom-in-95 duration-150">
                <h3 class="text-base font-bold text-red-600 uppercase tracking-tight">Sustento de Observación</h3>
                <p class="text-xs text-neutral-400 mt-1">Detalla los motivos del reclamo para que el área de Recursos Humanos pueda revisar tu caso.</p>
                <textarea v-model="observeForm.observation_message" placeholder="Ej: No se computaron mis horas extras de la primera semana o mi asignación familiar está desactualizada..." rows="4" class="w-full rounded-md border border-neutral-200 dark:border-neutral-800 mt-4 p-3 text-xs focus:outline-none focus:ring-1 focus:ring-red-500 bg-background font-sans dark:text-white"></textarea>
                <div class="flex justify-end gap-2 mt-4">
                    <button @click="selectedBoletaForObserve = null" :disabled="observeForm.processing" class="px-3 py-1.5 rounded-md border text-xs font-medium text-neutral-700 dark:text-neutral-300 bg-white dark:bg-neutral-800 hover:bg-neutral-50">Cancelar</button>
                    <button @click="submitObservation" :disabled="observeForm.processing || observeForm.observation_message.length < 5" class="px-3 py-1.5 rounded-md text-xs font-bold text-white bg-red-600 hover:bg-red-700 disabled:opacity-50">Enviar Observación</button>
                </div>
            </div>
        </div>

    </div>
</template>