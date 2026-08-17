<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { Eye, Send, KeyRound } from '@lucide/vue';

interface Role {
    id: number;
    name: string;
}
interface Boleta {
    id: number;
    user_id: number;
    type: string;
    mes: string;
    anio: number;
    status: 'Procesada' | 'Notificada' | 'Vista' | 'Firmada' | 'Observada';
    notified_at: string | null;
    read_at: string | null;
    signed_at: string | null;
    observation_message: string | null;
}

interface UserProfile {
    id: number;
    name: string;
    dni: string;
    phone: string | null;
    email: string;
    status: 'Activo' | 'Inactivo';
    created_at: string;
    roles: Role[];
    boletas: Boleta[]; // 🚀 Añadido el tipado de boletas
}

const props = defineProps<{
    user: UserProfile;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Gestión de Usuarios', href: '/users' },
            { title: 'Ficha de Personal', href: '#' },
        ],
    },
});

const page = usePage();
// 🎯 Capturamos dinámicamente el password plano e inyectado efímeramente por Laravel
const passwordGenerada = computed(
    () => (page.props as any).flash?.password_generada ?? null,
);
const isRegenerating = ref(false);

const activeTab = ref<'profile' | 'boletas'>('profile');

const getInitials = (name: string) => {
    return name
        .split(' ')
        .map((n) => n[0])
        .slice(0, 2)
        .join('')
        .toUpperCase();
};

// 🔐 EJECUTOR DE NUEVAS CREDENCIALES
const regenerarAcceso = () => {
    if (
        confirm(
            `¿Está seguro de regenerar el acceso para ${props.user.name}? Se enviará una nueva contraseña fuerte a su Outlook institucional.`,
        )
    ) {
        isRegenerating.value = true;
        router.post(
            `/users/${props.user.id}/regenerar-credenciales`,
            {},
            {
                preserveScroll: true,
                onSuccess: () => {
                    isRegenerating.value = false;
                },
                onError: () => {
                    isRegenerating.value = false;
                },
            },
        );
    }
};

const sendNotification = (id: number) => {
    // Localizamos la boleta exacta dentro de la colección cargada del usuario
    const boletaObjetivo = props.user.boletas.find((b: any) => b.id === id);

    router.post(
        `/boletas/${id}/notificar`,
        {},
        {
            preserveScroll: true,
            onSuccess: () => {
                // Sincronización reactiva inmediata en la tabla del perfil
                if (boletaObjetivo) {
                    boletaObjetivo.status = 'Notificada';
                    boletaObjetivo.notified_at = new Date().toISOString();
                }
            },
            onError: () => {
                alert(
                    'Ocurrió un error inesperado al despachar la alerta con Microsoft Graph.',
                );
            },
        },
    );
};
</script>

<template>

    <Head :title="`Perfil - ${user.name}`" />

    <div
        class="mx-auto flex h-full w-full max-w-3xl flex-1 flex-col gap-6 p-6 text-neutral-900 antialiased dark:text-neutral-100">
        <div class="flex items-center justify-between">
            <Link href="/users"
                class="inline-flex items-center gap-1.5 text-xs font-semibold tracking-wider text-neutral-500 uppercase transition-colors hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-neutral-200">
                ← Regresar al Listado
            </Link>
        </div>

        <div
            class="flex flex-col items-center gap-5 rounded-2xl border border-neutral-200/80 bg-white p-6 shadow-xs sm:flex-row dark:border-neutral-800 dark:bg-neutral-900">
            <div
                class="flex size-16 shrink-0 items-center justify-center rounded-full bg-neutral-900 text-xl font-black tracking-tight text-neutral-50 shadow-xs select-none dark:bg-neutral-100 dark:text-neutral-900">
                {{ getInitials(user.name) }}
            </div>
            <div class="space-y-1 text-center sm:text-left">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                    <h1 class="text-xl font-black tracking-tight text-neutral-950 dark:text-white">
                        {{ user.name }}
                    </h1>
                    <div>
                        <span :class="[
                            'inline-flex items-center rounded-md border px-2 py-0.5 text-[10px] font-bold tracking-wider uppercase',
                            user.status === 'Activo'
                                ? 'border-green-200 bg-green-50 text-green-700 dark:border-green-900/50 dark:bg-green-950/30 dark:text-green-400'
                                : 'border-neutral-200 bg-neutral-50 text-neutral-500 dark:border-neutral-800 dark:bg-neutral-950/30 dark:text-neutral-400',
                        ]">
                            {{ user.status }}
                        </span>
                    </div>
                </div>
                <p class="text-xs font-medium text-neutral-400">
                    Ficha de control operativo del Colegio Andino.
                </p>
            </div>
        </div>

        <div class="flex gap-2 border-b border-neutral-200 select-none dark:border-neutral-800">
            <button @click="activeTab = 'profile'" :class="[
                'border-b-2 px-4 py-2 text-xs font-bold tracking-wider uppercase transition-all',
                activeTab === 'profile'
                    ? 'border-neutral-900 text-neutral-900 dark:border-white dark:text-white'
                    : 'border-transparent text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-300',
            ]">
                Información del Trabajador
            </button>
            <button @click="activeTab = 'boletas'" :class="[
                'flex items-center gap-2 border-b-2 px-4 py-2 text-xs font-bold tracking-wider uppercase transition-all',
                activeTab === 'boletas'
                    ? 'border-neutral-900 text-neutral-900 dark:border-white dark:text-white'
                    : 'border-transparent text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-300',
            ]">
                Historial de Boletas
                <span
                    class="rounded-md bg-neutral-100 px-1.5 py-0.5 font-mono text-[10px] text-neutral-600 dark:bg-neutral-800 dark:text-neutral-400">
                    {{ user.boletas?.length ?? 0 }}
                </span>
            </button>
        </div>

        <div v-if="activeTab === 'profile'" class="space-y-6">
            <div v-if="passwordGenerada"
                class="flex animate-in flex-col justify-between gap-3 rounded-xl border border-dashed border-green-200 bg-green-50/50 p-4 duration-200 zoom-in-95 fade-in sm:flex-row sm:items-center dark:border-green-900/60 dark:bg-green-950/20">
                <div class="space-y-0.5">
                    <span
                        class="block text-xs font-bold tracking-wider text-green-700 uppercase dark:text-green-400">¡Nueva
                        Contraseña Generada con Éxito!</span>
                    <p class="text-xs text-neutral-500 dark:text-neutral-400">
                        Por seguridad, este bloque se destruirá si recarga la
                        página. Cópiela ahora:
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <code
                        class="rounded-lg border border-green-200 bg-white px-3 py-1.5 font-mono text-sm font-black text-neutral-900 shadow-2xs select-all">
                        {{ passwordGenerada }}
                    </code>
                </div>
            </div>
            <div
                class="divide-y divide-neutral-100 overflow-hidden rounded-2xl border border-neutral-200/80 bg-white shadow-2xs dark:divide-neutral-800 dark:border-neutral-800 dark:bg-neutral-900">
                <div class="space-y-4 p-6">
                    <h3 class="text-xs font-bold tracking-wider text-neutral-400 uppercase">
                        Identificación Legal
                    </h3>
                    <div class="grid grid-cols-1 items-baseline gap-1 py-1 sm:grid-cols-3 sm:gap-4">
                        <span
                            class="text-xs font-semibold text-neutral-400 uppercase sm:text-neutral-500 sm:normal-case">Nombre
                            Completo</span><span
                            class="text-sm font-bold text-neutral-900 sm:col-span-2 dark:text-neutral-100">{{ user.name
                            }}</span>
                    </div>
                    <div class="grid grid-cols-1 items-baseline gap-1 py-1 sm:grid-cols-3 sm:gap-4">
                        <span
                            class="text-xs font-semibold text-neutral-400 uppercase sm:text-neutral-500 sm:normal-case">DNI
                            (RENIEC)</span><span
                            class="font-mono text-sm font-bold tracking-wider text-neutral-900 sm:col-span-2 dark:text-neutral-100">{{
                                user.dni }}</span>
                    </div>
                </div>
                <div class="space-y-4 p-6">
                    <h3 class="text-xs font-bold tracking-wider text-neutral-400 uppercase">
                        Canales de Comunicación
                    </h3>
                    <div class="grid grid-cols-1 items-baseline gap-1 py-1 sm:grid-cols-3 sm:gap-4">
                        <span
                            class="text-xs font-semibold text-neutral-400 uppercase sm:text-neutral-500 sm:normal-case">Celular
                            Personal</span><span
                            class="font-mono text-sm text-neutral-900 sm:col-span-2 dark:text-neutral-100">{{ user.phone
                                || 'No registrado' }}</span>
                    </div>
                    <div class="grid grid-cols-1 items-baseline gap-1 py-1 sm:grid-cols-3 sm:gap-4">
                        <span
                            class="text-xs font-semibold text-neutral-400 uppercase sm:text-neutral-500 sm:normal-case">Correo
                            Electrónico</span><span
                            class="truncate text-sm font-medium text-blue-600 sm:col-span-2 dark:text-blue-400">{{
                                user.email }}</span>
                    </div>
                </div>
                <div class="space-y-4 p-6">
                    <h3 class="text-xs font-bold tracking-wider text-neutral-400 uppercase">
                        Credenciales y Seguridad
                    </h3>
                    <div class="grid grid-cols-1 items-center gap-1 py-1 sm:grid-cols-3 sm:gap-4">
                        <span
                            class="text-xs font-semibold text-neutral-400 uppercase sm:text-neutral-500 sm:normal-case">Roles
                            Asignados</span>
                        <div class="flex flex-wrap gap-1.5 sm:col-span-2">
                            <span v-for="role in user.roles" :key="role.id"
                                class="inline-flex items-center rounded-lg border border-neutral-200 bg-neutral-100 px-2.5 py-1 text-xs font-bold tracking-tight text-neutral-800 dark:border-neutral-700/60 dark:bg-neutral-800 dark:text-neutral-200">{{
                                    role.name }}</span>
                        </div>
                        <button type="button" @click="regenerarAcceso" :disabled="isRegenerating"
                            class="inline-flex shrink-0 items-center gap-1.5 rounded-xl border border-neutral-300 bg-white px-3 py-2 text-xs font-bold text-neutral-700 shadow-2xs transition-colors hover:bg-neutral-50 disabled:cursor-not-allowed disabled:opacity-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-neutral-700/50">
                            <KeyRound :class="[
                                'size-3.5 text-amber-500',
                                isRegenerating ? 'animate-spin' : '',
                            ]" />
                            <span>{{
                                isRegenerating
                                    ? 'Generando...'
                                    : 'Regenerar Credencial'
                            }}</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <Link :href="`/users/${user.id}/edit`"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-neutral-900 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition-colors hover:bg-neutral-800 sm:w-auto dark:bg-neutral-50 dark:text-neutral-900 dark:hover:bg-neutral-200">
                    Modificar Información de Personal
                </Link>
            </div>
        </div>

        <div v-else class="space-y-4">
            <div v-if="!user.boletas || user.boletas.length === 0"
                class="rounded-2xl border border-dashed border-neutral-200 p-12 text-center dark:border-neutral-800">
                <p class="text-sm text-neutral-400 italic">
                    Este trabajador no cuenta con boletas de pago registradas en
                    la intranet.
                </p>
            </div>

            <div v-else
                class="overflow-hidden rounded-2xl border border-neutral-200/80 bg-white shadow-2xs dark:border-neutral-800 dark:bg-neutral-900">
                <table class="w-full border-collapse text-left text-xs">
                    <thead>
                        <tr
                            class="border-b border-neutral-100 bg-neutral-50 font-bold tracking-wider text-neutral-400 uppercase select-none dark:border-neutral-800 dark:bg-neutral-950">
                            <th class="p-4">Periodo</th>
                            <th class="p-4">Tipo Documento</th>
                            <th class="p-4">Estado Interno</th>
                            <th class="p-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100 font-medium dark:divide-neutral-800">
                        <tr v-for="boleta in user.boletas" :key="boleta.id"
                            class="transition-colors hover:bg-neutral-50/50 dark:hover:bg-neutral-950/40">
                            <td class="p-4 font-mono font-bold">
                                {{ boleta.mes }} / {{ boleta.anio }}
                            </td>
                            <td class="p-4 text-neutral-500 dark:text-neutral-400">
                                {{ boleta.type }}
                            </td>
                            <td class="p-4">
                                <span :class="[
                                    'rounded-md border px-2 py-0.5 text-[10px] font-bold uppercase',
                                    boleta.status === 'Firmada'
                                        ? 'border-green-200 bg-green-50 text-green-700 dark:border-green-900/40 dark:bg-green-950/20 dark:text-green-400'
                                        : boleta.status === 'Observada'
                                            ? 'border-red-200 bg-red-50 text-red-700 dark:border-red-900/40 dark:bg-red-950/20 dark:text-red-400'
                                            : boleta.status === 'Vista'
                                                ? 'border-blue-200 bg-blue-50 text-blue-700 dark:border-blue-900/40 dark:bg-blue-950/20 dark:text-blue-400'
                                                : boleta.status === 'Notificada'
                                                    ? 'border-sky-200 bg-sky-50 text-sky-700 dark:border-sky-900/40 dark:bg-sky-950/20 dark:text-sky-400'
                                                    : 'border-neutral-200 bg-neutral-50 text-neutral-700 dark:border-neutral-900/40 dark:bg-neutral-950/20 dark:text-neutral-400', // Procesada
                                ]">
                                    {{ boleta.status }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button v-if="boleta.status !== 'Firmada'" @click="sendNotification(boleta.id)"
                                        type="button" title="Enviar Notificación"
                                        class="rounded-md p-1.5 text-sky-600 transition-colors hover:bg-sky-50 dark:text-sky-400 dark:hover:bg-sky-500/10">
                                        <Send class="h-4 w-4" />
                                    </button>

                                    <Link :href="`/boletas/${boleta.id}`" title="Ver documento"
                                        class="rounded-md p-1.5 text-neutral-500 transition-colors hover:bg-neutral-100 dark:text-neutral-400 dark:hover:bg-neutral-800">
                                        <Eye class="h-4 w-4" />
                                    </Link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
