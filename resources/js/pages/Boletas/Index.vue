<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import { Head, usePage, Link, router } from '@inertiajs/vue3';
import { PenTool } from '@lucide/vue';
import SignatureCanvas from '@/components/SignatureCanvas.vue';
import ConfirmSignModal from '@/components/ConfirmSignModal.vue';
import Pagination from '@/components/Pagination.vue'; // Reutilizas el mismo componente

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
    status: 'Procesada' | 'Notificada' | 'Vista' | 'Firmada' | 'Observada';
    signed_at: string | null;
    notified_at: string | null;
    observation_message: string | null;
    user?: Empleado;
}

interface PaginatedBoletas {
    data: Boleta[];
    links: { url: string | null; label: string; active: boolean; }[];
    current_page: number;
    last_page: number;
    total: number;
}

const props = defineProps<{
    boletas: PaginatedBoletas;
    filters: any;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Documentos Laborales', href: '/boletas' },
        ],
    },
});

const page = usePage();
const authUser = computed(() => (page.props.auth as any).user);
const isAdminOrRRHH = computed(() =>
    authUser.value?.roles?.includes('Administrador') ||
    authUser.value?.roles?.includes('Recursos Humanos')
);

// --- FILTROS REACTIVOS LOCALES ---
const urlParams = new URLSearchParams(window.location.search);
const search = ref(urlParams.get('search') || '');
const monthFilter = ref(urlParams.get('month') || 'all');
const typeFilter = ref(urlParams.get('type') || 'all');

const mesesAño = [
    'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
];

const filteredBoletas = computed(() => props.boletas.data);

const hasActiveFilters = computed(() => search.value.trim() !== '' || monthFilter.value !== 'all' || typeFilter.value !== 'all');

// 3. Función centralizada para disparar la búsqueda asíncrona al servidor
const aplicarFiltros = () => {
    // 🎯 Corrección 2: Removemos 'preserveScroll' y 'preserveState' ya que reload() lo hace por defecto
    router.reload({
        data: {
            page: 1, 
            search: search.value.trim() || undefined,
            month: monthFilter.value !== 'all' ? monthFilter.value : undefined,
            type: typeFilter.value !== 'all' ? typeFilter.value : undefined,
        },
        only: ['boletas'], // Optimización crucial para actualizar solo la tabla
    });
};

// 4. Agregamos 'debouncing' manual al input de búsqueda para no saturar al servidor en cada tecla
let timeoutBusqueda: ReturnType<typeof setTimeout>;
watch(search, () => {
    clearTimeout(timeoutBusqueda);
    timeoutBusqueda = setTimeout(() => {
        aplicarFiltros();
    }, 150); // 
});

// 5. Los selectores reaccionan de forma inmediata al hacer clic
watch([monthFilter, typeFilter], () => {
    aplicarFiltros();
});

// 6. Resetear limpia los filtros tanto en el cliente como en la URL del servidor
const resetFilters = () => {
    search.value = '';
    monthFilter.value = 'all';
    typeFilter.value = 'all';
    
    router.reload({
        data: { search: undefined, month: undefined, type: undefined, page: 1 },
        only: ['boletas']
    });
};

// --- SISTEMA DE FIRMA DIGITAL ---
const showSignatureModal = ref(false);
const showConfirmModal = ref(false);
const boletaIdSujeta = ref<number | null>(null);

const signBoleta = (id: number) => {
    boletaIdSujeta.value = id; // Asignamos el ID desde el inicio

    if (authUser.value.signature_path) {
        // Si ya tiene firma registrada, abrimos el nuevo modal de confirmación estética
        showConfirmModal.value = true;
    } else {
        // Si no tiene, abrimos el canvas para que dibuje
        showSignatureModal.value = true;
    }
};

const executeSignatureFromModal = () => {
    if (boletaIdSujeta.value) {
        router.post(`/boletas/${boletaIdSujeta.value}/firmar`, {}, {
            preserveScroll: true,
            onSuccess: () => {
                showConfirmModal.value = false;
                boletaIdSujeta.value = null; // Limpiamos el ID tras el éxito
            }
        });
    }
};

const submitSignatureAndSign = (base64Data: string) => {
    if (boletaIdSujeta.value) {
        router.post(`/boletas/${boletaIdSujeta.value}/firmar`, {
            signature_base64: base64Data
        }, {
            preserveScroll: true,
            onSuccess: () => {
                showSignatureModal.value = false;
                boletaIdSujeta.value = null;
                authUser.value.signature_path = 'registrado';
            }
        });
    }
};

// 🚀 CONTROL DE ENVÍO Y MUTACIÓN EN CALIENTE
const sendNotification = (id: number) => {
    // Localizamos la boleta exacta dentro de los datos que nos pasó el controlador
    const boletaObjetivo = props.boletas.data.find(b => b.id === id);

    router.post(`/boletas/${id}/notificar`, {}, {
        preserveScroll: true, // Mantiene la posición del scroll del usuario en la tabla
        onSuccess: () => {
            // 🔥 MAGIA REACTIVA: Sincronizamos la fila inmediatamente
            if (boletaObjetivo) {
                boletaObjetivo.status = 'Notificada';
                boletaObjetivo.notified_at = new Date().toISOString();
            }
        },
        onError: () => {
            alert('Ocurrió un error inesperado al despachar la alerta con Microsoft Graph.');
        }
    });
};

const deleteBoleta = (id: number) => {
    if (confirm('¿Estás completamente seguro de eliminar de forma permanente este documento laboral?')) {
        router.delete(`/boletas/${id}`, {
            preserveScroll: true,
            onSuccess: () => {
                // El registro se limpia y la paginación se recalcula sola en caliente
            }
        });
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

const currentUrl = computed(() => page.url);
const isPersonalView = computed(() => currentUrl.value.startsWith('/mis-boletas'));

const avatarPalette = [
    'bg-indigo-50 text-indigo-700 dark:bg-indigo-400/10 dark:text-indigo-400',
    'bg-teal-50 text-teal-700 dark:bg-teal-400/10 dark:text-teal-400',
    'bg-amber-50 text-amber-700 dark:bg-amber-400/10 dark:text-amber-400',
    'bg-rose-50 text-rose-700 dark:bg-rose-400/10 dark:text-rose-400',
    'bg-violet-50 text-violet-700 dark:bg-violet-400/10 dark:text-violet-400',
    'bg-sky-50 text-sky-700 dark:bg-sky-400/10 dark:text-sky-400',
];

const getAvatarClass = (u: any) => {
    const fullName = `${u.first_name} ${u.paternal_surname}`;
    let hash = 0;
    for (let i = 0; i < fullName.length; i++) hash += fullName.charCodeAt(i);
    return avatarPalette[hash % avatarPalette.length];
};

const getInitials = (u: any) => {
    const a = u.first_name?.[0] ?? '';
    const b = u.paternal_surname?.[0] ?? '';
    return (a + b).toUpperCase() || '?';
};
</script>

<template>

    <Head title="Gestión de Boletas y Documentos" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100">
                        {{ isPersonalView ? 'Mis Boletas de Pago' : 'Documentos Laborales del Plantel' }}
                    </h1>
                    <span
                        class="inline-flex items-center rounded-full bg-neutral-100 px-2.5 py-0.5 text-xs font-medium text-neutral-600 dark:bg-neutral-800 dark:text-neutral-400">
                        {{ boletas.total }} documentos
                    </span>
                </div>
                <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">
                    Panel centralizado para el control, firma digital y auditoría de boletas de pago y CTS.
                </p>
            </div>
            <Link v-if="isAdminOrRRHH" href="/boletas/create"
                class="inline-flex items-center justify-center rounded-md bg-neutral-900 px-4 py-2 text-sm font-medium text-neutral-50 shadow transition-colors hover:bg-neutral-800 dark:bg-neutral-50 dark:text-neutral-900 dark:hover:bg-neutral-50/90">
                + Inyección Masiva (PLAME/CTS)
            </Link>
        </div>

        <div
            class="relative flex-1 rounded-xl border border-sidebar-border/70 bg-white dark:border-sidebar-border dark:bg-neutral-900 overflow-hidden">
            <div
                class="flex flex-col gap-3 border-b border-neutral-200 p-4 sm:flex-row sm:items-center sm:justify-between dark:border-neutral-800">
                <div class="relative w-full sm:max-w-xs">
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-neutral-400"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="11" cy="11" r="7" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                    <input v-model="search" type="text" placeholder="Buscar por DNI, empleado o año..."
                        class="w-full rounded-md border border-neutral-300 bg-white py-2 pl-9 pr-3 text-sm text-neutral-900 placeholder:text-neutral-400 focus:border-neutral-400 focus:outline-none dark:border-neutral-700 dark:bg-neutral-950 dark:text-neutral-100" />
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <select v-model="monthFilter"
                        class="rounded-md border border-neutral-300 bg-white py-2 px-2.5 text-sm text-neutral-700 focus:border-neutral-400 focus:outline-none dark:border-neutral-700 dark:bg-neutral-950 dark:text-neutral-300">
                        <option value="all">Todos los meses</option>
                        <option v-for="mes in mesesAño" :key="mes" :value="mes">{{ mes }}</option>
                    </select>

                    <select v-model="typeFilter"
                        class="rounded-md border border-neutral-300 bg-white py-2 px-2.5 text-sm text-neutral-700 focus:border-neutral-400 focus:outline-none dark:border-neutral-700 dark:bg-neutral-950 dark:text-neutral-300">
                        <option value="all">Todos los tipos</option>
                        <option value="Boleta Mensual">Boleta Mensual</option>
                        <option value="Gratificación">Gratificación</option>
                        <option value="CTS">CTS</option>
                        <option value="Otro">Otro</option>
                    </select>

                    <button v-if="hasActiveFilters" type="button" @click="resetFilters"
                        class="text-xs font-medium text-neutral-500 underline-offset-2 hover:text-neutral-900 dark:text-neutral-400">
                        Limpiar filtros
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table v-if="filteredBoletas.length"
                    class="w-full text-sm text-left text-neutral-500 dark:text-neutral-400">
                    <thead
                        class="text-xs uppercase bg-neutral-50 dark:bg-neutral-800/50 text-neutral-700 dark:text-neutral-300 border-b border-sidebar-border/70">
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
                        <tr v-for="(boleta, index) in filteredBoletas" :key="boleta.id" :class="[
                            'border-l-[3px] transition-colors',
                            boleta.status === 'Firmada' ? 'border-l-green-500' :
                                boleta.status === 'Observada' ? 'border-l-red-500 bg-rose-50/30 dark:bg-rose-950/5' :
                                    boleta.status === 'Vista' ? 'border-l-blue-600' :
                                        boleta.status === 'Notificada' ? 'border-l-sky-400' :
                                            'border-l-neutral-300 dark:border-l-neutral-700', // Procesada o por defecto (Gris)
                            'hover:bg-neutral-50/50 dark:hover:bg-neutral-800/30'
                        ]">
                            <td class="px-4 py-4 text-center font-mono text-xs text-neutral-400">
                                {{ (boletas.current_page - 1) * 10 + index + 1 }}
                            </td>

                            <td v-if="isAdminOrRRHH" class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3" v-if="boleta.user">
                                    <span
                                        :class="['flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-xs font-semibold shadow-xs', getAvatarClass(boleta.user)]">
                                        {{ getInitials(boleta.user) }}
                                    </span>
                                    <div class="flex flex-col">
                                        <span class="font-medium text-neutral-900 dark:text-neutral-100">
                                            {{ boleta.user.paternal_surname }} {{ boleta.user.maternal_surname }}, {{
                                                boleta.user.first_name }}
                                        </span>
                                        <span class="text-xs font-mono text-neutral-400">DNI: {{ boleta.user.dni
                                        }}</span>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    :class="['inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset', getDocTypeBadgeClass(boleta.type)]">
                                    {{ boleta.type }}
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap font-medium text-neutral-700 dark:text-neutral-300">
                                {{ boleta.mes }} - {{ boleta.anio }}
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                <span :class="['inline-flex items-center gap-1.5 text-xs font-medium',
                                    boleta.status === 'Firmada' ? 'text-green-700 dark:text-green-400' :
                                        boleta.status === 'Observada' ? 'text-red-700 dark:text-red-400' :
                                            boleta.status === 'Vista' ? 'text-blue-700 dark:text-blue-400' :
                                                boleta.status === 'Notificada' ? 'text-sky-600 dark:text-sky-400' :
                                                    'text-neutral-500 dark:text-neutral-400' // Procesada
                                ]">
                                    <span :class="['h-1.5 w-1.5 rounded-full',
                                        boleta.status === 'Firmada' ? 'bg-green-500' :
                                            boleta.status === 'Observada' ? 'bg-red-500' :
                                                boleta.status === 'Vista' ? 'bg-blue-600' :
                                                    boleta.status === 'Notificada' ? 'bg-sky-400' :
                                                        'bg-neutral-400' // Procesada
                                    ]" />
                                    {{ boleta.status }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-xs text-neutral-600 dark:text-neutral-400">
                                <div v-if="boleta.status === 'Firmada' && boleta.signed_at" class="flex flex-col">
                                    <span class="font-semibold text-green-600 dark:text-green-400">Firmado
                                        Conformidad</span>
                                    <span class="text-[11px] font-mono tracking-tight">{{
                                        formatTimestamp(boleta.signed_at) }}</span>
                                </div>
                                <div v-else-if="boleta.status === 'Observada'" class="flex flex-col max-w-xs">
                                    <span class="font-semibold text-red-500">Motivo de Reclamo:</span>
                                    <span class="truncate italic text-[11px]"
                                        :title="boleta.observation_message ?? ''">"{{ boleta.observation_message
                                        }}"</span>
                                </div>
                                <span v-else class="text-neutral-400 italic">Pendiente de firma</span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a :href="`/boletas/${boleta.id}`" title="Ver documento"
                                        class="rounded-md p-1.5 text-neutral-500 transition-colors hover:bg-neutral-100 dark:text-neutral-400 dark:hover:bg-neutral-800">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.5">
                                            <path
                                                d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                    </a>

                                    <template v-if="isPersonalView">
                                        <button v-if="boleta.status !== 'Firmada'" @click="signBoleta(boleta.id)"
                                            type="button" title="Firmar Conformidad"
                                            class="rounded-md p-1.5 text-green-600 hover:bg-green-50 dark:text-green-400 dark:hover:bg-green-500/10">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="1.5">
                                                <path d="M12 20h9M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z" />
                                            </svg>
                                        </button>
                                    </template>

                                    <template v-else-if="isAdminOrRRHH">
                                        <button v-if="boleta.status !== 'Firmada'" @click="sendNotification(boleta.id)"
                                            type="button" title="Enviar Notificación"
                                            class="rounded-md p-1.5 text-sky-600 hover:bg-sky-50 dark:text-sky-400 dark:hover:bg-sky-500/10">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="1.5">
                                                <line x1="22" y1="2" x2="11" y2="13" />
                                                <polygon points="22 2 15 22 11 13 2 9 22 2" />
                                            </svg>
                                        </button>

                                        <button @click="deleteBoleta(boleta.id)" type="button"
                                            title="Eliminar Permanente"
                                            class="rounded-md p-1.5 text-neutral-500 hover:bg-red-50 hover:text-red-600 dark:text-neutral-400 dark:hover:bg-red-500/10">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="1.5">
                                                <path
                                                    d="M3 6h18M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                            </svg>
                                        </button>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ══════════════════════════════════════════
             COMPONENTE DE PAGINACIÓN ADAPTADO (BOLETAS)
             ══════════════════════════════════════════ -->
                    <Pagination :meta="boletas" label="boletas en total" />


        <div v-if="showSignatureModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs p-4">
            <div
                class="w-full max-w-lg shadow-xl bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-2xl p-6">
                <div class="space-y-0.5 mb-4">
                    <h3
                        class="text-base font-bold text-neutral-900 dark:text-neutral-50 flex items-center gap-2 uppercase tracking-tight">
                        <PenTool class="size-4 text-amber-500" /> Registrar Firma Digital
                    </h3>
                    <p class="text-xs text-neutral-400">Plasma tu trazo dentro del recuadro para archivarlo en tu cuenta
                        y
                        proceder con el visado.</p>
                </div>
                <SignatureCanvas @save="submitSignatureAndSign" @cancel="showSignatureModal = false" />
            </div>
        </div>
        <ConfirmSignModal :open="showConfirmModal" @close="() => { showConfirmModal = false; boletaIdSujeta = null; }"
            @confirm="executeSignatureFromModal" />
    </div>
</template>