<script setup lang="ts">
import { computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { dashboard } from '@/routes';
import { index as boletasIndexRoute } from '@/routes/boletas';
import { Card, CardContent } from '@/components/ui/card';
import { FileSpreadsheet, Users, UserSquare2, ArrowRight } from '@lucide/vue';

// Configuración de la barra superior fija de navegación
defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard(),
            },
        ],
    },
});

// Control de roles compartido por Inertia
const page = usePage();
const authUser = computed(() => (page.props.auth as any).user);
const isAdminOrRRHH = computed(() =>
    authUser.value?.roles?.includes('Administrador') ||
    authUser.value?.roles?.includes('Recursos Humanos')
);
</script>

<template>
    <Head title="Dashboard" />

    <div class="flex h-full flex-1 flex-col gap-8 rounded-xl p-6 max-w-6xl mx-auto">
        
        <!-- BIENVENIDA MINIMALISTA GRANDE -->
        <div class="space-y-2 border-b pb-6">
            <span class="text-xs font-mono font-bold tracking-widest uppercase text-neutral-400 dark:text-neutral-500">
                Módulo Interno — {{ authUser?.roles?.[0] ?? 'Docente / Administrativo' }}
            </span>
            <h1 class="text-4xl font-extrabold tracking-tight text-neutral-900 dark:text-neutral-100">
                ¡Bienvenido, {{ authUser?.name }}!
            </h1>
        </div>

        <!-- RECUADROS GRANDES CENTRADOS (Estilo Mosaico SUNAT / PLAME) -->
        <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 justify-center items-center">
            
            <!-- TARJETA 1: MIS BOLETAS (Personal) -->
            <Link href="/mis-boletas" class="group block w-full">
                <Card class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 hover:border-blue-400 dark:hover:border-blue-500 hover:shadow-xl transition-all duration-300 rounded-2xl overflow-hidden">
                    <CardContent class="p-8 flex flex-col items-center text-center justify-between min-h-[260px]">
                        <!-- Icono Superior Gigante -->
                        <div class="p-4 bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 rounded-2xl transition-transform group-hover:scale-110 duration-300">
                            <UserSquare2 class="size-10" />
                        </div>
                        
                        <!-- Título y Subtítulo Centrales -->
                        <div class="space-y-1 my-4">
                            <h3 class="text-xl font-black tracking-tight text-neutral-900 dark:text-neutral-50">Mis Boletas</h3>
                            <p class="text-xs text-neutral-400 dark:text-neutral-500">Consulta tus boletas de pago</p>
                        </div>

                        <!-- Badge Operativo de Alcance -->
                        <div class="mb-4">
                            <span class="px-3 py-1 text-[10px] font-bold uppercase tracking-widest rounded-full bg-blue-100/60 dark:bg-blue-950/50 text-blue-700 dark:text-blue-400 border border-blue-200/50 dark:border-blue-900/50">
                                Personal
                            </span>
                        </div>

                        <!-- Enlace Inferior Centrado -->
                        <div class="flex items-center gap-1.5 text-xs font-bold text-blue-600 dark:text-blue-400 group-hover:translate-x-1 transition-transform">
                            Acceder <ArrowRight class="size-3.5" />
                        </div>
                    </CardContent>
                </Card>
            </Link>

            <!-- TARJETA 2: BOLETAS DE PAGO (Planilla General - Solo Admin / RRHH) -->
            <Link 
                v-if="isAdminOrRRHH" 
                :href="boletasIndexRoute().url" 
                class="group block w-full"
            >
                <Card class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 hover:border-amber-400 dark:hover:border-amber-500 hover:shadow-xl transition-all duration-300 rounded-2xl overflow-hidden">
                    <CardContent class="p-8 flex flex-col items-center text-center justify-between min-h-[260px]">
                        <!-- Icono Superior Gigante -->
                        <div class="p-4 bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 rounded-2xl transition-transform group-hover:scale-110 duration-300">
                            <FileSpreadsheet class="size-10" />
                        </div>
                        
                        <!-- Título y Subtítulo Centrales -->
                        <div class="space-y-1 my-4">
                            <h3 class="text-xl font-black tracking-tight text-neutral-900 dark:text-neutral-50">Boletas de Pago</h3>
                            <p class="text-xs text-neutral-400 dark:text-neutral-500">Carga de boletas de pago</p>
                        </div>

                        <!-- Badge Operativo de Alcance -->
                        <div class="mb-4">
                            <span class="px-3 py-1 text-[10px] font-bold uppercase tracking-widest rounded-full bg-amber-100/60 dark:bg-amber-950/50 text-amber-700 dark:text-amber-400 border border-amber-200/50 dark:border-amber-900/50">
                                Administración
                            </span>
                        </div>

                        <!-- Enlace Inferior Centrado -->
                        <div class="flex items-center gap-1.5 text-xs font-bold text-amber-600 dark:text-amber-400 group-hover:translate-x-1 transition-transform">
                            Acceder <ArrowRight class="size-3.5" />
                        </div>
                    </CardContent>
                </Card>
            </Link>

            <!-- TARJETA 3: EMPLEADOS (Gestión de Personal - Solo Admin / RRHH) -->
            <Link 
                v-if="isAdminOrRRHH" 
                href="/users" 
                class="group block w-full"
            >
                <Card class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 hover:border-purple-400 dark:hover:border-purple-500 hover:shadow-xl transition-all duration-300 rounded-2xl overflow-hidden">
                    <CardContent class="p-8 flex flex-col items-center text-center justify-between min-h-[260px]">
                        <!-- Icono Superior Gigante -->
                        <div class="p-4 bg-purple-50 dark:bg-purple-950/30 text-purple-600 dark:text-purple-400 rounded-2xl transition-transform group-hover:scale-110 duration-300">
                            <Users class="size-10" />
                        </div>
                        
                        <!-- Título y Subtítulo Centrales -->
                        <div class="space-y-1 my-4">
                            <h3 class="text-xl font-black tracking-tight text-neutral-900 dark:text-neutral-50">Empleados</h3>
                            <p class="text-xs text-neutral-400 dark:text-neutral-500">Gestión del personal y usuarios</p>
                        </div>

                        <!-- Badge Operativo de Alcance -->
                        <div class="mb-4">
                            <span class="px-3 py-1 text-[10px] font-bold uppercase tracking-widest rounded-full bg-purple-100/60 dark:bg-purple-950/50 text-purple-700 dark:text-purple-400 border border-purple-200/50 dark:border-purple-900/50">
                                Administración
                            </span>
                        </div>

                        <!-- Enlace Inferior Centrado -->
                        <div class="flex items-center gap-1.5 text-xs font-bold text-purple-600 dark:text-purple-400 group-hover:translate-x-1 transition-transform">
                            Acceder <ArrowRight class="size-3.5" />
                        </div>
                    </CardContent>
                </Card>
            </Link>

        </div>
    </div>
</template>