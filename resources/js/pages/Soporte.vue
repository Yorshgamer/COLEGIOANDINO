<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Mail, ShieldCheck, MessageSquare } from '@lucide/vue';

// Recibimos las propiedades inyectadas desde el closure de Laravel
defineProps<{
    encargado: string;
    area: string;
    email: string;
    whatsapp: string;
}>();
</script>

<template>
    <Head title="Soporte Técnico" />
        <div class="px-4 py-6 max-w-2xl mx-auto space-y-6">
            <Heading
                title="Soporte Técnico Especializado"
                description="Canal de atención interno para incidencias de red, plataforma académica y boletas de pago."
            />
            <Card class="border border-neutral-200 dark:border-neutral-800 shadow-sm bg-white dark:bg-neutral-900 overflow-hidden">
                <CardHeader class="bg-neutral-50/50 dark:bg-neutral-950/20 border-b border-neutral-100 dark:border-neutral-800">
                    <div class="flex items-center gap-2.5">
                        <div class="p-2 rounded-lg bg-neutral-900 text-white dark:bg-neutral-100 dark:text-neutral-900">
                            <ShieldCheck class="size-5" />
                        </div>
                        <div>
                            <CardTitle class="text-base font-bold text-neutral-900 dark:text-neutral-100">
                                {{ area }}
                            </CardTitle>
                            <CardDescription class="text-xs text-neutral-400">
                                {{ encargado }}
                            </CardDescription>
                        </div>
                    </div>
                </CardHeader>
                
                <CardContent class="p-6 space-y-5">
                    <p class="text-sm text-neutral-500 leading-relaxed dark:text-neutral-400">
                        Si presentas dificultadesbo requieres asistencia técnica, comunícate directamente a través de los siguientes canales oficiales:
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <a :href="`mailto:${email}`" class="flex items-center gap-3 p-3.5 rounded-xl border border-neutral-100 dark:border-neutral-800 hover:bg-neutral-50 dark:hover:bg-neutral-950/50 transition-colors group">
                            <div class="p-2 rounded-lg bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400 group-hover:scale-105 transition-transform">
                                <Mail class="size-4" />
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-neutral-400 uppercase tracking-wider">Correo Electrónico</span>
                                <span class="text-xs font-medium text-neutral-700 dark:text-neutral-300 break-all">{{ email }}</span>
                            </div>
                        </a>

                        <a :href="`https://wa.me/${whatsapp.replace('+', '')}?text=Hola%20Ing.%20Villena,%20necesito%20soporte%20con%20el%20sistema.`" target="_blank" class="flex items-center gap-3 p-3.5 rounded-xl border border-neutral-100 dark:border-neutral-800 hover:bg-neutral-50 dark:hover:bg-neutral-950/50 transition-colors group">
                            <div class="p-2 rounded-lg bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400 group-hover:scale-105 transition-transform">
                                <MessageSquare class="size-4" />
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-amber-500 uppercase tracking-wider">WhatsApp Institucional</span>
                                <span class="text-xs font-medium text-neutral-700 dark:text-neutral-300">Enviar Mensaje Directo</span>
                            </div>
                        </a>
                    </div>
                </CardContent>
            </Card>
        </div>
</template>