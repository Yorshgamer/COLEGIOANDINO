<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
// 1. IMPORTACIÓN CORREGIDA: Llamamos al layout real del sistema para romper el bucle
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { UploadCloud, FileSpreadsheet, FileText, CheckCircle, AlertCircle, ArrowLeft } from '@lucide/vue';

// Estado reactivo local para gestionar la cola de archivos en el navegador
const archivosSeleccionados = ref<File[]>([]);
const isDragging = ref(false);

// Formulario reactivo de Inertia
const form = useForm({
    archivos: [] as File[],
});

// 2. CONFIGURACIÓN DE BREADCRUMBS: Valores fijos en string para acoplarse a la barra oficial
defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Documentos Laborales', href: '/boletas' },
            { title: 'Inyección Masiva', href: '/boletas/create' },
        ],
    },
});

// Captura de archivos por explorador tradicional
const handleFileChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files) {
        agregarArchivos(Array.from(target.files));
    }
};

// Captura de archivos por arrastre (Drag & Drop)
const handleDrop = (e: DragEvent) => {
    isDragging.value = false;
    if (e.dataTransfer && e.dataTransfer.files) {
        agregarArchivos(Array.from(e.dataTransfer.files));
    }
};

// Validador e inyector de la cola local
const agregarArchivos = (files: File[]) => {
    const validFiles = files.filter(file => {
        const ext = file.name.split('.').pop()?.toLowerCase();
        return ext === 'pdf' || ext === 'xml';
    });
    
    const nombresExistentes = archivosSeleccionados.value.map(f => f.name);
    const nuevosFiltrados = validFiles.filter(file => !nombresExistentes.includes(file.name));

    archivosSeleccionados.value = [...archivosSeleccionados.value, ...nuevosFiltrados];
};

const removerArchivo = (index: number) => {
    archivosSeleccionados.value.splice(index, 1);
};

// Despacho del lote masivo al controlador
const procesarLote = () => {
    if (archivosSeleccionados.value.length === 0) return;

    form.archivos = archivosSeleccionados.value;
    form.post('/boletas/carga-masiva', {
        preserveScroll: true,
        onSuccess: () => {
            archivosSeleccionados.value = [];
            form.reset();
        }
    });
};
</script>

<template>
    <Head title="Carga Masiva de Planillas" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-6">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100">
                    Inyección Masiva de Documentos
                </h1>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    Carga automatizada de boletas de pago (PLAME) y constancias de CTS mediante procesamiento de nomenclatura oficial.
                </p>
            </div>
            
            <Link 
                href="/boletas"
                class="inline-flex items-center justify-center rounded-md border border-neutral-300 bg-white px-4 py-2 text-sm font-medium text-neutral-700 shadow-xs transition-colors hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-neutral-800/50"
            >
                <ArrowLeft class="size-4 mr-2" /> Volver al Historial
            </Link>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
            <Card class="bg-blue-50/40 dark:bg-blue-950/10 border-blue-100 dark:border-blue-900/50">
                <CardHeader class="pb-2 p-4">
                    <CardTitle class="text-xs font-bold text-blue-700 dark:text-blue-400 uppercase tracking-wider">Formato PLAME (Boletas)</CardTitle>
                </CardHeader>
                <CardContent class="text-[11px] text-neutral-600 dark:text-neutral-400 space-y-1.5 p-4 pt-0">
                    <p class="font-mono bg-white dark:bg-neutral-950 px-2 py-1.5 rounded border text-[10px] text-neutral-800 dark:text-neutral-300 break-all">
                        RUC_PERIODO_TIPODOC_NUMERODOC_r08.xml/.pdf
                    </p>
                    <p><span class="font-bold text-neutral-700 dark:text-neutral-300">Ejemplo:</span> 20161572366_202512_01_09842053_r08.xml</p>
                </CardContent>
            </Card>

            <Card class="bg-amber-50/40 dark:bg-amber-950/10 border-amber-100 dark:border-amber-900/50">
                <CardHeader class="pb-2 p-4">
                    <CardTitle class="text-xs font-bold text-amber-700 dark:text-amber-400 uppercase tracking-wider">Formato Liquidación CTS</CardTitle>
                </CardHeader>
                <CardContent class="text-[11px] text-neutral-600 dark:text-neutral-400 space-y-1.5 p-4 pt-0">
                    <p class="font-mono bg-white dark:bg-neutral-950 px-2 py-1.5 rounded border text-[10px] text-neutral-800 dark:text-neutral-300">
                        CTS_DNI_PERIODO.pdf
                    </p>
                    <p><span class="font-bold text-neutral-700 dark:text-neutral-300">Ejemplo:</span> CTS_20052990_202511.pdf</p>
                </CardContent>
            </Card>
        </div>

        <div v-if="$page.props.flash?.error_masivo" class="p-4 bg-rose-50 dark:bg-rose-950/20 border border-rose-100 dark:border-rose-900/30 rounded-xl space-y-2 animate-in fade-in">
            <div class="flex items-center gap-2 text-rose-700 dark:text-rose-400 text-xs font-bold">
                <AlertCircle class="size-4 shrink-0" />
                <span>Inconsistencias detectadas en el lote (Archivos omitidos):</span>
            </div>
            <ul class="list-disc list-inside text-[10px] font-mono text-rose-600 dark:text-rose-400 max-h-40 overflow-y-auto space-y-1 bg-white/50 dark:bg-black/10 p-2 rounded-lg">
                <li v-for="(err, idx) in $page.props.flash.error_masivo" :key="idx">{{ err }}</li>
            </ul>
        </div>

        <Card class="relative flex-1 rounded-xl border border-sidebar-border/70 bg-white p-4 dark:border-sidebar-border dark:bg-neutral-900 overflow-hidden">
            <CardContent class="p-2 h-full flex flex-col justify-between">
                <div
                    @dragover.prevent="isDragging = true"
                    @dragleave.prevent="isDragging = false"
                    @drop.prevent="handleDrop"
                    :class="[
                        'border-2 border-dashed rounded-xl p-12 flex flex-col items-center justify-center gap-3 transition-colors cursor-pointer select-none',
                        isDragging 
                            ? 'border-amber-500 bg-amber-500/[0.02] dark:bg-amber-500/[0.01]' 
                            : 'border-neutral-200 dark:border-neutral-800 hover:bg-neutral-50/50 dark:hover:bg-neutral-900/50'
                    ]"
                    @click="($refs.fileInput as HTMLInputElement).click()"
                >
                    <input ref="fileInput" type="file" multiple accept=".pdf,.xml" class="hidden" @change="handleFileChange" />
                    <UploadCloud :class="['size-10 transition-colors', isDragging ? 'text-amber-500' : 'text-neutral-400']" />
                    <div class="text-center">
                        <p class="text-xs font-bold text-neutral-700 dark:text-neutral-300">Arrastra tus archivos PLAME o CTS aquí</p>
                        <p class="text-[11px] text-neutral-400 mt-1">Soporta selección múltiple simultánea de archivos PDF y XML exportados de SUNAT</p>
                    </div>
                </div>

                <div v-if="archivosSeleccionados.length > 0" class="mt-6 space-y-4 animate-in fade-in duration-200">
                    <div class="flex items-center justify-between border-b pb-2">
                        <span class="text-xs font-bold text-neutral-600 dark:text-neutral-400">
                            Documentos listos en cola ({{ archivosSeleccionados.length }})
                        </span>
                        <Button 
                            size="sm" 
                            @click="procesarLote" 
                            :disabled="form.processing" 
                            class="bg-neutral-900 hover:bg-neutral-800 text-white dark:bg-neutral-50 dark:text-neutral-900 dark:hover:bg-neutral-50/90 font-medium text-xs h-9 shadow-xs px-4"
                        >
                            <CheckCircle class="size-4 mr-2" /> Inyectar Lote de Planillas
                        </Button>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-64 overflow-y-auto p-1">
                        <div 
                            v-for="(file, idx) in archivosSeleccionados" 
                            :key="file.name" 
                            class="flex items-center justify-between p-2 rounded-lg border border-neutral-100 dark:border-neutral-800 bg-neutral-50/30 dark:bg-neutral-900/30 text-xs"
                        >
                            <div class="flex items-center gap-2 truncate pr-2">
                                <component 
                                    :is="file.name.endsWith('.xml') ? FileSpreadsheet : FileText" 
                                    :class="['size-4 shrink-0', file.name.endsWith('.xml') ? 'text-teal-600 dark:text-teal-500' : 'text-rose-600 dark:text-rose-500']" 
                                />
                                <span class="truncate font-mono text-[11px] text-neutral-700 dark:text-neutral-300" :title="file.name">
                                    {{ file.name }}
                                </span>
                            </div>
                            <Button 
                                size="icon" 
                                variant="ghost" 
                                @click.stop="removerArchivo(idx)" 
                                class="size-6 text-neutral-400 hover:text-rose-600 dark:hover:text-rose-400 rounded-full shrink-0"
                            >
                                ✕
                            </Button>
                        </div>
                    </div>
                </div>
            </CardContent>
        </Card>

    </div>
</template>