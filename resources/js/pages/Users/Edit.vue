<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { Eye, EyeOff, KeyRound, UserRound, ArrowLeft } from '@lucide/vue';

interface Role { id: number; name: string; }
interface User {
    id: number;
    name: string;
    first_name: string;
    paternal_surname: string;
    maternal_surname: string;
    dni: string;
    phone: string | null;
    email: string;
    status: 'Activo' | 'Inactivo';
    roles: Role[];
}

const props = defineProps<{ user: User; roles: Role[] }>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Gestión de Usuarios', href: '/users' },
            { title: 'Modificar Datos', href: '#' },
        ],
    },
});

const form = useForm({
    first_name: props.user.first_name,
    paternal_surname: props.user.paternal_surname,
    maternal_surname: props.user.maternal_surname,
    dni: props.user.dni,
    phone: props.user.phone || '',
    email: props.user.email,
    role: props.user.roles[0]?.name || 'Usuario',
    status: props.user.status,
    password: '',
});

const showPassword = ref(false);

const submit = () => {
    form.put(`/users/${props.user.id}`, {
        preserveScroll: true,
        onSuccess: () => form.reset('password')
    });
};

// --- Identidad de Avatar unificada con tus componentes Show e Index ---
const avatarPalette = [
    'bg-indigo-50 text-indigo-700 dark:bg-indigo-400/10 dark:text-indigo-400',
    'bg-teal-50 text-teal-700 dark:bg-teal-400/10 dark:text-teal-400',
    'bg-amber-50 text-amber-700 dark:bg-amber-400/10 dark:text-amber-400',
    'bg-rose-50 text-rose-700 dark:bg-rose-400/10 dark:text-rose-400',
    'bg-violet-50 text-violet-700 dark:bg-violet-400/10 dark:text-violet-400',
    'bg-sky-50 text-sky-700 dark:bg-sky-400/10 dark:text-sky-400',
];

const getAvatarClass = (name: string) => {
    let hash = 0;
    for (let i = 0; i < name.length; i++) hash += name.charCodeAt(i);
    return avatarPalette[hash % avatarPalette.length];
};

const getInitials = () => {
    const a = props.user.first_name?.[0] ?? props.user.name?.[0] ?? '';
    const b = props.user.paternal_surname?.[0] ?? '';
    return (a + b).toUpperCase() || '?';
};
</script>

<template>
    <Head :title="`Editar - ${user.first_name}`" />

    <div class="flex h-full flex-1 flex-col gap-6 p-6 max-w-3xl mx-auto w-full">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b pb-5">
            <div class="flex items-center gap-4">
                <span :class="['flex h-12 w-14 shrink-0 items-center justify-center rounded-full text-sm font-bold shadow-xs', getAvatarClass(user.name)]">
                    {{ getInitials() }}
                </span>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100">Modificar Ficha Laboral</h1>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400">Editando credenciales de: <span class="font-semibold text-neutral-700 dark:text-neutral-300">{{ user.name }}</span></p>
                </div>
            </div>
            
            <Link href="/users" class="inline-flex items-center justify-center rounded-md border border-neutral-300 bg-white px-3 py-2 text-xs font-medium text-neutral-700 shadow-sm hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300">
                <ArrowLeft class="size-3.5 mr-1.5" /> Volver al Listado
            </Link>
        </div>

        <div class="rounded-xl border border-sidebar-border/70 bg-white p-6 dark:border-sidebar-border dark:bg-neutral-900 shadow-xs">
            <form @submit.prevent="submit" class="space-y-5">
                
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-neutral-400 flex items-center gap-1 mb-3">
                        <UserRound class="size-3" /> Datos de Identidad
                    </span>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400 uppercase mb-1">Nombres</label>
                            <input v-model="form.first_name" type="text" required class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm focus:outline-none focus:border-neutral-400 dark:border-neutral-700 dark:bg-neutral-950 dark:text-white" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400 uppercase mb-1">Ap. Paterno</label>
                            <input v-model="form.paternal_surname" type="text" required class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm focus:outline-none focus:border-neutral-400 dark:border-neutral-700 dark:bg-neutral-950 dark:text-white" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400 uppercase mb-1">Ap. Materno</label>
                            <input v-model="form.maternal_surname" type="text" required class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm focus:outline-none focus:border-neutral-400 dark:border-neutral-700 dark:bg-neutral-950 dark:text-white" />
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-neutral-100 dark:border-neutral-800/60 pt-4">
                    <div>
                        <label class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400 uppercase mb-1">Documento de Identidad (DNI)</label>
                        <input v-model="form.dni" type="text" maxlength="8" pattern="\d{8}" required class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm font-mono focus:outline-none focus:border-neutral-400 dark:border-neutral-700 dark:bg-neutral-950 dark:text-white" />
                        <div v-if="form.errors.dni" class="text-xs text-red-500 mt-1">{{ form.errors.dni }}</div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400 uppercase mb-1">Número de Celular</label>
                        <input v-model="form.phone" type="text" maxlength="9" pattern="9\d{8}" class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm font-mono focus:outline-none focus:border-neutral-400 dark:border-neutral-700 dark:bg-neutral-950 dark:text-white" />
                    </div>
                </div>

                <div class="border-t border-neutral-100 dark:border-neutral-800/60 pt-4">
                    <label class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400 uppercase mb-1">Correo Electrónico Institucional</label>
                    <input v-model="form.email" type="email" required class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm focus:outline-none focus:border-neutral-400 dark:border-neutral-700 dark:bg-neutral-950 dark:text-white" />
                    <div v-if="form.errors.email" class="text-xs text-red-500 mt-1">{{ form.errors.email }}</div>
                </div>

                <div class="p-4 rounded-xl border border-dashed border-neutral-200 dark:border-neutral-800 bg-neutral-50/40 dark:bg-neutral-950/20">
                    <label class="block text-xs font-bold text-neutral-800 dark:text-neutral-200 uppercase mb-1 tracking-tight flex items-center gap-1.5">
                        <KeyRound class="size-3.5 text-amber-500" /> Forzar Cambio de Contraseña (TI)
                    </label>
                    <p class="text-[11px] text-neutral-400 dark:text-neutral-500 mb-3">Deja este campo vacío si el docente desea conservar sus accesos actuales. Al escribir un texto, se anulará la anterior.</p>
                    
                    <div class="relative flex items-center">
                        <input 
                            v-model="form.password" 
                            :type="showPassword ? 'text' : 'password'" 
                            placeholder="Escribe la nueva contraseña institucional..."
                            class="w-full rounded-md border border-neutral-300 bg-white pr-10 px-3 py-2 text-sm focus:outline-none focus:border-neutral-400 dark:border-neutral-700 dark:bg-neutral-950 dark:text-white font-mono" 
                        />
                        <button 
                            type="button" 
                            @click="showPassword = !showPassword"
                            class="absolute right-3 text-neutral-400 hover:text-neutral-700 dark:hover:text-neutral-200 focus:outline-none"
                        >
                            <component :is="showPassword ? EyeOff : Eye" class="size-4" />
                        </button>
                    </div>
                    <div v-if="form.errors.password" class="text-xs text-red-500 mt-1">{{ form.errors.password }}</div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-neutral-100 dark:border-neutral-800/60 pt-4">
                    <div>
                        <label class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400 uppercase mb-1">Rol Asignado</label>
                        <select v-model="form.role" required class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm focus:outline-none focus:border-neutral-400 dark:border-neutral-700 dark:bg-neutral-950 dark:text-white appearance-none cursor-pointer">
                            <option v-for="role in roles" :key="role.id" :value="role.name">{{ role.name }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400 uppercase mb-1">Estado Operativo</label>
                        <select v-model="form.status" required class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm focus:outline-none focus:border-neutral-400 dark:border-neutral-700 dark:bg-neutral-950 dark:text-white appearance-none cursor-pointer">
                            <option value="Activo">Activo</option>
                            <option value="Inactivo">Inactivo</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-neutral-200 dark:border-neutral-800">
                    <Link href="/users" class="px-4 py-2 text-sm font-medium text-neutral-600 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-neutral-200">
                        Cancelar
                    </Link>
                    <button type="submit" :disabled="form.processing" class="rounded-md bg-neutral-900 px-4 py-2 text-sm font-medium text-neutral-50 shadow-sm transition-colors hover:bg-neutral-800 dark:bg-neutral-50 dark:text-neutral-900 dark:hover:bg-neutral-200">
                        Actualizar Ficha
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>