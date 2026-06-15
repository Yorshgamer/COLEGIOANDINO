<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';

interface Role {
    id: number;
    name: string;
}

defineProps<{ roles: Role[] }>();

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
    role: 'Usuario', // Rol operativo estándar por defecto
    status: 'Activo',
});

const submit = () => {
    form.post('/users');
};
</script>

<template>
    <Head title="Registrar Empleado" />

    <div class="flex h-full flex-1 flex-col gap-4 p-6 max-w-3xl mx-auto w-full">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100">Registrar Nuevo Trabajador</h1>
            <p class="text-sm text-neutral-500 dark:text-neutral-400">Completa la ficha de identidad obligatoria para la apertura del control interno.</p>
        </div>

        <div class="rounded-xl border border-sidebar-border/70 bg-white p-6 dark:border-sidebar-border dark:bg-neutral-900">
            <form @submit.prevent="submit" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-neutral-700 dark:text-neutral-300 uppercase mb-1">Nombres</label>
                        <input v-model="form.first_name" type="text" required class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm focus:outline-none focus:border-neutral-900 dark:border-neutral-700 dark:bg-neutral-800" />
                        <div v-if="form.errors.first_name" class="text-xs text-red-500 mt-1">{{ form.errors.first_name }}</div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-neutral-700 dark:text-neutral-300 uppercase mb-1">Apellido Paterno</label>
                        <input v-model="form.paternal_surname" type="text" required class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm focus:outline-none focus:border-neutral-900 dark:border-neutral-700 dark:bg-neutral-800" />
                        <div v-if="form.errors.paternal_surname" class="text-xs text-red-500 mt-1">{{ form.errors.paternal_surname }}</div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-neutral-700 dark:text-neutral-300 uppercase mb-1">Apellido Materno</label>
                        <input v-model="form.maternal_surname" type="text" required class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm focus:outline-none focus:border-neutral-900 dark:border-neutral-700 dark:bg-neutral-800" />
                        <div v-if="form.errors.maternal_surname" class="text-xs text-red-500 mt-1">{{ form.errors.maternal_surname }}</div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-neutral-700 dark:text-neutral-300 uppercase mb-1">DNI (8 dígitos)</label>
                        <input v-model="form.dni" type="text" maxlength="8" pattern="\d{8}" required placeholder="Escribe los 8 números" class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm font-mono focus:outline-none focus:border-neutral-900 dark:border-neutral-700 dark:bg-neutral-800" />
                        <div v-if="form.errors.dni" class="text-xs text-red-500 mt-1">{{ form.errors.dni }}</div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-neutral-700 dark:text-neutral-300 uppercase mb-1">Celular (9 dígitos)</label>
                        <input v-model="form.phone" type="text" maxlength="9" pattern="9\d{8}" placeholder="Ej: 9########" class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm font-mono focus:outline-none focus:border-neutral-900 dark:border-neutral-700 dark:bg-neutral-800" />
                        <div v-if="form.errors.phone" class="text-xs text-red-500 mt-1">{{ form.errors.phone }}</div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-neutral-700 dark:text-neutral-300 uppercase mb-1">Correo Institucional</label>
                        <input v-model="form.email" type="email" required class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm focus:outline-none focus:border-neutral-900 dark:border-neutral-700 dark:bg-neutral-800" />
                        <div v-if="form.errors.email" class="text-xs text-red-500 mt-1">{{ form.errors.email }}</div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-neutral-700 dark:text-neutral-300 uppercase mb-1">Contraseña de Sistema</label>
                        <input v-model="form.password" type="password" required class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm focus:outline-none focus:border-neutral-900 dark:border-neutral-700 dark:bg-neutral-800" />
                        <div v-if="form.errors.password" class="text-xs text-red-500 mt-1">{{ form.errors.password }}</div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-neutral-700 dark:text-neutral-300 uppercase mb-1">Rol Asignado</label>
                        <select v-model="form.role" required class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm focus:outline-none focus:border-neutral-900 dark:border-neutral-700 dark:bg-neutral-800">
                            <option v-for="role in roles" :key="role.id" :value="role.name">{{ role.name }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-neutral-700 dark:text-neutral-300 uppercase mb-1">Estado Operativo</label>
                        <select v-model="form.status" required class="w-full rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm focus:outline-none focus:border-neutral-900 dark:border-neutral-700 dark:bg-neutral-800">
                            <option value="Activo">Activo</option>
                            <option value="Inactivo">Inactivo</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-neutral-200 dark:border-neutral-800">
                    <Link href="/users" class="px-4 py-2 text-sm font-medium text-neutral-700 hover:text-neutral-900 dark:text-neutral-300">Cancelar</Link>
                    <button type="submit" :disabled="form.processing" class="rounded-md bg-neutral-900 px-4 py-2 text-sm font-medium text-neutral-50 shadow hover:bg-neutral-800 dark:bg-neutral-50 dark:text-neutral-900 disabled:opacity-50">
                        Guardar Personal
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>