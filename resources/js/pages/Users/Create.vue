<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { Eye, EyeOff, KeyRound, UserRound, ArrowLeft } from '@lucide/vue';

interface Role {
    id: number;
    name: string;
}

defineProps<{ roles: Role[] }>();

// Configuración de la barra superior institucional (Breadcrumbs fijos)
defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Gestión de Usuarios', href: '/users' },
            { title: 'Registrar Empleado', href: '/users/create' },
        ],
    },
});

const form = useForm({
    first_name: '',
    paternal_surname: '',
    maternal_surname: '',
    dni: '',
    phone: '',
    email: '',
    password: '',
    role: 'Usuario', 
    status: 'Activo',
});

const showPassword = ref(false);

const submit = () => {
    form.post('/users', {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Registrar Empleado" />

    <div class="flex h-full flex-1 flex-col gap-6 p-6 max-w-3xl mx-auto w-full">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b pb-5">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100">Registrar Nuevo Trabajador</h1>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">Completa la ficha de identidad obligatoria para la apertura del control interno.</p>
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
                            <div v-if="form.errors.first_name" class="text-xs text-red-500 mt-1">{{ form.errors.first_name }}</div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400 uppercase mb-1">Ap. Paterno</label>
                            <input v-model="form.paternal_surname" type="text" required class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm focus:outline-none focus:border-neutral-400 dark:border-neutral-700 dark:bg-neutral-950 dark:text-white" />
                            <div v-if="form.errors.paternal_surname" class="text-xs text-red-500 mt-1">{{ form.errors.paternal_surname }}</div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400 uppercase mb-1">Ap. Materno</label>
                            <input v-model="form.maternal_surname" type="text" required class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm focus:outline-none focus:border-neutral-400 dark:border-neutral-700 dark:bg-neutral-950 dark:text-white" />
                            <div v-if="form.errors.maternal_surname" class="text-xs text-red-500 mt-1">{{ form.errors.maternal_surname }}</div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-neutral-100 dark:border-neutral-800/60 pt-4">
                    <div>
                        <label class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400 uppercase mb-1">DNI (8 dígitos)</label>
                        <input v-model="form.dni" type="text" maxlength="8" pattern="\d{8}" required placeholder="Escribe los 8 números" class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm font-mono focus:outline-none focus:border-neutral-400 dark:border-neutral-700 dark:bg-neutral-950 dark:text-white" />
                        <div v-if="form.errors.dni" class="text-xs text-red-500 mt-1">{{ form.errors.dni }}</div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400 uppercase mb-1">Celular (9 dígitos)</label>
                        <input v-model="form.phone" type="text" maxlength="9" pattern="9\d{8}" placeholder="Ej: 9########" class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm font-mono focus:outline-none focus:border-neutral-400 dark:border-neutral-700 dark:bg-neutral-950 dark:text-white" />
                        <div v-if="form.errors.phone" class="text-xs text-red-500 mt-1">{{ form.errors.phone }}</div>
                    </div>
                </div>

                <div class="border-t border-neutral-100 dark:border-neutral-800/60 pt-4">
                    <label class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400 uppercase mb-1">Correo Electrónico Institucional</label>
                    <input v-model="form.email" type="email" required placeholder="ejemplo@colegioandino.edu.pe" class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm focus:outline-none focus:border-neutral-400 dark:border-neutral-700 dark:bg-neutral-950 dark:text-white" />
                    <div v-if="form.errors.email" class="text-xs text-red-500 mt-1">{{ form.errors.email }}</div>
                </div>

                <div class="p-4 rounded-xl border border-dashed border-neutral-200 dark:border-neutral-800 bg-neutral-50/40 dark:bg-neutral-950/20">
                    <label class="block text-xs font-bold text-neutral-800 dark:text-neutral-200 uppercase mb-1 tracking-tight flex items-center gap-1.5">
                        <KeyRound class="size-3.5 text-amber-500" /> Contraseña de Primer Acceso
                    </label>
                    <p class="text-[11px] text-neutral-400 dark:text-neutral-500 mb-3">Asigna una clave inicial segura (mínimo 6 caracteres). Te sugerimos usar el número de DNI como patrón base.</p>
                    
                    <div class="relative flex items-center">
                        <input 
                            v-model="form.password" 
                            :type="showPassword ? 'text' : 'password'" 
                            required
                            placeholder="Escribe la clave para el maestro o administrativo..."
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
                        <div v-if="form.errors.role" class="text-xs text-red-500 mt-1">{{ form.errors.role }}</div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400 uppercase mb-1">Estado Operativo Inicial</label>
                        <select v-model="form.status" required class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm focus:outline-none focus:border-neutral-400 dark:border-neutral-700 dark:bg-neutral-950 dark:text-white appearance-none cursor-pointer">
                            <option value="Activo">Activo</option>
                            <option value="Inactivo">Inactivo</option>
                        </select>
                        <div v-if="form.errors.status" class="text-xs text-red-500 mt-1">{{ form.errors.status }}</div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-neutral-200 dark:border-neutral-800">
                    <Link href="/users" class="px-4 py-2 text-sm font-medium text-neutral-600 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-neutral-200">
                        Cancelar
                    </Link>
                    <button type="submit" :disabled="form.processing" class="rounded-md bg-neutral-900 px-4 py-2 text-sm font-medium text-neutral-50 shadow-sm transition-colors hover:bg-neutral-800 dark:bg-neutral-50 dark:text-neutral-900 dark:hover:bg-neutral-200 disabled:opacity-50">
                        Guardar Personal
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>