<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, useForm, Link, usePage, router } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { UploadCloud, FileSpreadsheet, FileText, CheckCircle, AlertCircle, ArrowLeft, User, Calendar } from '@lucide/vue';

// Definimos la prop que viene de Laravel
const props = defineProps<{
    existentes: string[]; // Formato: "DNI_Tipo_Mes_Anio"
}>();

// 🎯 INSTANCIA REACTIVA DE LA PÁGINA (Para capturar los flash del backend)
const page = usePage();
const flash = computed(() => (page.props.flash as any) || {});
const erroresMasivos = computed(() => flash.value.error_masivo || []);
const mensajeSuccess = computed(() => flash.value.success);

// Estructura de la cola local enriquecida con metadatos
interface ArchivoEnCola {
    file: File;
    dni: string;
    mes: string;
    anio: number;
    type: 'Boleta Mensual' | 'CTS';
    yaExiste: boolean;
}

const archivosSeleccionados = ref<ArchivoEnCola[]>([]);
const isDragging = ref(false);

const form = useForm({
    archivos: [] as File[],
});

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Documentos Laborales', href: '/boletas' },
            { title: 'Inyección Masiva', href: '/boletas/create' },
        ],
    },
});

const mesesMapeo: Record<string, string> = {
    '01': 'Enero', '02': 'Febrero', '03': 'Marzo', '04': 'Abril',
    '05': 'Mayo', '06': 'Junio', '07': 'Julio', '08': 'Agosto',
    '09': 'Septiembre', '10': 'Octubre', '11': 'Noviembre', '12': 'Diciembre'
};

// --- PARSER EN CLIENTE BASADO EN TU LOGICA DE BACKEND ---
const analizarArchivo = (file: File): ArchivoEnCola => {
    const name = file.name;
    let dni = 'Desconocido';
    let mes = 'Desconocido';
    let anio = new Date().getFullYear();
    let type: 'Boleta Mensual' | 'CTS' = 'Boleta Mensual';

    // CASO 1: CTS -> CTS_DNI_PERIODO.pdf
    if (name.toUpperCase().startsWith('CTS_')) {
        const parts = name.replace('.pdf', '').split('_');
        if (parts.length >= 3) {
            dni = parts[1].trim();
            const periodo = parts[2];
            anio = parseInt(periodo.substring(0, 4)) || anio;
            const codMes = periodo.substring(4, 6);
            mes = mesesMapeo[codMes] ?? 'Desconocido';
            type = 'CTS';
        }
    }
    // CASO 2: PLAME -> RUC_PERIODO_TIPODOC_NUMERODOC_r08.ext
    else {
        const cleanName = name.replace('.pdf', '').replace('.xml', '');
        const parts = cleanName.split('_');
        if (parts.length >= 4) {
            const periodo = parts[1];
            dni = parts[3];
            anio = parseInt(periodo.substring(0, 4)) || anio;
            const codMes = periodo.substring(4, 6);
            mes = mesesMapeo[codMes] ?? 'Desconocido';
            type = 'Boleta Mensual';
        }
    }

    // Verificamos si coincide con la matriz que enviamos desde el controlador
    const llaveVerificacion = `${dni}_${type}_${mes}_${anio}`;
    const yaExiste = props.existentes.includes(llaveVerificacion);

    return { file, dni, mes, anio, type, yaExiste };
};

const handleFileChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files) {
        agregarArchivos(Array.from(target.files));
    }
};

const handleDrop = (e: DragEvent) => {
    isDragging.value = false;
    if (e.dataTransfer && e.dataTransfer.files) {
        agregarArchivos(Array.from(e.dataTransfer.files));
    }
};

const agregarArchivos = (files: File[]) => {
    const validFiles = files.filter(file => {
        const ext = file.name.split('.').pop()?.toLowerCase();
        return ext === 'pdf' || ext === 'xml';
    });

    const nombresExistentes = archivosSeleccionados.value.map(item => item.file.name);

    validFiles.forEach(file => {
        if (!nombresExistentes.includes(file.name)) {
            archivosSeleccionados.value.push(analizarArchivo(file));
        }
    });
};

const removerArchivo = (index: number) => {
    archivosSeleccionados.value.splice(index, 1);
};

// Evaluamos si hay algún duplicado estricto en cola para alertar al botón
const tieneDuplicadosEnCola = computed(() => archivosSeleccionados.value.some(item => item.yaExiste));

const procesandoBloques = ref(false);
const bloqueActual = ref(0);
const totalBloques = ref(0);

const procesarLote = async () => {
    // Filtramos únicamente los archivos válidos y nuevos
    const limpios = archivosSeleccionados.value.filter(item => !item.yaExiste);

    if (limpios.length === 0) {
        alert('No hay archivos nuevos válidos para procesar en este lote.');
        return;
    }

    procesandoBloques.value = true;

    // 🎯 Definimos el tamaño del bloque seguro (15 archivos por petición)
    const TAMANO_BLOQUE = 15;
    const todosLosArchivos = limpios.map(item => item.file);

    totalBloques.value = Math.ceil(todosLosArchivos.length / TAMANO_BLOQUE);
    bloqueActual.value = 0;

    // Ejecutamos el envío secuencial por bloques
    for (let i = 0; i < todosLosArchivos.length; i += TAMANO_BLOQUE) {
        bloqueActual.value++;

        // Cortamos el sub-bloque de archivos
        const bloqueSegmento = todosLosArchivos.slice(i, i + TAMANO_BLOQUE);

        // Creamos un FormData nativo para simular el envío del formulario tradicional
        const formData = new FormData();
        bloqueSegmento.forEach(archivo => {
            formData.append('archivos[]', archivo);
        });

        try {
            // Enviamos de forma síncrona el bloque usando router.post de Inertia
            await new Promise((resolve, reject) => {
                router.post('/boletas/carga-masiva', formData, {
                    preserveScroll: true,
                    preserveState: true, // Evita que se limpie la UI a mitad del proceso
                    onSuccess: () => resolve(true),
                    onError: (err) => reject(err),
                });
            });
        } catch (error) {
            console.error(`Error procesando el bloque ${bloqueActual.value}`, error);
            alert(`Ocurrió un inconveniente al subir el bloque ${bloqueActual.value}. El proceso se detuvo.`);
            procesandoBloques.value = false;
            return; // Abortamos para no corromper el resto
        }
    }

    // 🏁 Al finalizar todos los bloques con éxito, limpiamos la cola local
    archivosSeleccionados.value = [];
    procesandoBloques.value = false;
    alert('¡Todos los bloques han sido enviados y procesados con éxito en el servidor!');
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
                    Carga automatizada de boletas de pago (PLAME) y constancias de CTS mediante procesamiento de
                    nomenclatura oficial.
                </p>
            </div>

            <Link href="/boletas"
                class="inline-flex items-center justify-center rounded-md border border-neutral-300 bg-white px-4 py-2 text-sm font-medium text-neutral-700 shadow-xs transition-colors hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300">
                <ArrowLeft class="size-4 mr-2" /> Volver al Historial
            </Link>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
            <Card class="bg-blue-50/40 dark:bg-blue-950/10 border-blue-100 dark:border-blue-900/50">
                <CardHeader class="pb-2 p-4">
                    <CardTitle class="text-xs font-bold text-blue-700 dark:text-blue-400 uppercase tracking-wider">
                        Formato PLAME (Boletas)</CardTitle>
                </CardHeader>
                <CardContent class="text-[11px] text-neutral-600 dark:text-neutral-400 space-y-1.5 p-4 pt-0">
                    <p
                        class="font-mono bg-white dark:bg-neutral-950 px-2 py-1.5 rounded border text-[10px] text-neutral-800 dark:text-neutral-300 break-all">
                        RUC_PERIODO_TIPODOC_NUMERODOC_r08.xml/.pdf
                    </p>
                    <p><span class="font-bold text-neutral-700 dark:text-neutral-300">Ejemplo:</span>
                        20161572366_202512_01_09842053_r08.xml</p>
                </CardContent>
            </Card>

            <Card class="bg-amber-50/40 dark:bg-amber-950/10 border-amber-100 dark:border-amber-900/50">
                <CardHeader class="pb-2 p-4">
                    <CardTitle class="text-xs font-bold text-amber-700 dark:text-amber-400 uppercase tracking-wider">
                        Formato Liquidación CTS</CardTitle>
                </CardHeader>
                <CardContent class="text-[11px] text-neutral-600 dark:text-neutral-400 space-y-1.5 p-4 pt-0">
                    <p
                        class="font-mono bg-white dark:bg-neutral-950 px-2 py-1.5 rounded border text-[10px] text-neutral-800 dark:text-neutral-300">
                        CTS_DNI_PERIODO.pdf
                    </p>
                    <p><span class="font-bold text-neutral-700 dark:text-neutral-300">Ejemplo:</span>
                        CTS_20052990_202511.pdf</p>
                </CardContent>
            </Card>
        </div>

        <!-- ═══════════════════════════════════════════
🟢 MENSAJE DE ÉXITO
═══════════════════════════════════════════ -->
        <div v-if="mensajeSuccess"
            class="rounded-2xl border border-emerald-200 bg-emerald-50/60 p-5 shadow-xs animate-in fade-in slide-in-from-top-4 duration-300">
            <div class="flex items-start gap-3">
                <CheckCircle class="size-5 text-emerald-600 shrink-0 mt-0.5" />

                <div>
                    <h4 class="text-xs font-black uppercase tracking-wider text-emerald-800">
                        Carga completada correctamente
                    </h4>

                    <p class="text-sm text-emerald-700 mt-1">
                        {{ mensajeSuccess }}
                    </p>
                </div>
            </div>
        </div>

        <!-- ══════════════════════════════════════════
     🔴 PANEL DE INCONSISTENCIAS ENCONTRADAS POR EL SERVIDOR
     ══════════════════════════════════════════ -->
        <div v-if="erroresMasivos.length"
            class="rounded-2xl border border-red-200 bg-red-50/50 p-5 shadow-xs space-y-3.5 animate-in fade-in slide-in-from-top-4 duration-300">
            <div class="flex items-start gap-3 text-red-900">
                <AlertCircle class="size-5 text-red-600 shrink-0 mt-0.5 animate-pulse" />
                <div>
                    <h4 class="text-xs font-black uppercase tracking-wider">Inconsistencias detectadas en el servidor
                        ({{ erroresMasivos.length }})</h4>
                    <p class="text-[11px] text-red-700/80 mt-0.5">Los siguientes archivos fueron omitidos del lote por
                        fallas de consistencia o falta de alta del personal:</p>
                </div>
            </div>

            <!-- Caja contenedora con scroll vertical protegido si hay demasiados errores -->
            <div
                class="max-h-52 overflow-y-auto border border-red-200/60 rounded-xl bg-white p-1 divide-y divide-red-100 shadow-inner">
                <div v-for="(err, idx) in erroresMasivos" :key="idx"
                    class="flex items-start gap-2.5 px-4 py-2.5 text-xs text-neutral-700 font-sans font-medium">
                    <span class="text-red-500 font-bold shrink-0 mt-0.5">•</span>
                    <span class="leading-relaxed">{{ err }}</span>
                </div>
            </div>
        </div>

        <Card
            class="relative flex-1 rounded-xl border border-sidebar-border/70 bg-white p-4 dark:border-sidebar-border dark:bg-neutral-900 overflow-hidden">
            <CardContent class="p-2 h-full flex flex-col justify-between">
                <div @dragover.prevent="isDragging = true" @dragleave.prevent="isDragging = false"
                    @drop.prevent="handleDrop" :class="[
                        'border-2 border-dashed rounded-xl p-10 flex flex-col items-center justify-center gap-3 transition-colors cursor-pointer select-none',
                        isDragging
                            ? 'border-amber-500 bg-amber-500/[0.02]'
                            : 'border-neutral-200 dark:border-neutral-800 hover:bg-neutral-50/50 dark:hover:bg-neutral-900/50'
                    ]" @click="($refs.fileInput as HTMLInputElement).click()">
                    <input ref="fileInput" type="file" multiple accept=".pdf,.xml" class="hidden"
                        @change="handleFileChange" />
                    <UploadCloud
                        :class="['size-10 transition-colors', isDragging ? 'text-amber-500' : 'text-neutral-400']" />
                    <div class="text-center">
                        <p class="text-xs font-bold text-neutral-700 dark:text-neutral-300">Arrastra tus archivos PLAME
                            o CTS aquí</p>
                        <p class="text-[11px] text-neutral-400 mt-1">Soporta selección múltiple simultánea de archivos
                            PDF y XML exportados de SUNAT</p>
                    </div>
                </div>

                <div v-if="archivosSeleccionados.length > 0" class="mt-6 space-y-4 animate-in fade-in duration-200">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b pb-2 gap-2">
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-neutral-700 dark:text-neutral-300">
                                Documentos en cola de inyección ({{ archivosSeleccionados.length }})
                            </span>
                            <span v-if="tieneDuplicadosEnCola"
                                class="text-[10px] text-red-500 font-semibold flex items-center gap-1 mt-0.5 animate-pulse">
                                <AlertCircle class="size-3" /> Atención: Los archivos marcados en rojo serán omitidos
                                automáticamente.
                            </span>
                        </div>
                        <!-- Coloca esto justo arriba del botón de enviar o de la lista de archivos -->
                        <div v-if="procesandoBloques"
                            class="w-full bg-neutral-100 dark:bg-neutral-800 p-4 rounded-xl border border-neutral-200 dark:border-neutral-700 space-y-2 animate-in fade-in">
                            <div
                                class="flex justify-between items-center text-xs font-bold text-neutral-700 dark:text-neutral-300">
                                <span class="uppercase tracking-wider text-amber-600 animate-pulse">Procesando boletas...</span>
                                <span>Bloque {{ bloqueActual }} de {{ totalBloques }}</span>
                            </div>
                            <!-- Barra de progreso fluida -->
                            <div class="w-full h-2 bg-neutral-200 dark:bg-neutral-700 rounded-full overflow-hidden">
                                <div class="h-full bg-[#1A1D56] transition-all duration-300 ease-out"
                                    :style="{ width: `${(bloqueActual / totalBloques) * 100}%` }"></div>
                            </div>
                        </div>
                        <Button size="sm" @click="procesarLote" :disabled="form.processing"
                            class="bg-neutral-900 hover:bg-neutral-800 text-white dark:bg-neutral-50 dark:text-neutral-900 dark:hover:bg-neutral-200 font-bold text-xs h-9 shadow-xs px-4">
                            <CheckCircle class="size-4 mr-2" /> Inyectar Archivos Nuevos
                        </Button>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-80 overflow-y-auto p-1">
                        <div v-for="(item, idx) in archivosSeleccionados" :key="item.file.name" :class="[
                            'flex items-center justify-between p-3 rounded-xl border transition-all relative overflow-hidden',
                            item.yaExiste
                                ? 'border-red-200 dark:border-red-950/50 bg-red-50/40 dark:bg-red-950/5 opacity-85'
                                : 'border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-950 shadow-2xs'
                        ]">
                            <div class="flex items-center gap-3 truncate pr-4 w-full">
                                <div
                                    :class="['p-2 rounded-lg shrink-0', item.yaExiste ? 'bg-red-100 text-red-600 dark:bg-red-950 dark:text-red-400' : 'bg-neutral-100 text-neutral-600 dark:bg-neutral-900 dark:text-neutral-400']">
                                    <component :is="item.file.name.endsWith('.xml') ? FileSpreadsheet : FileText"
                                        class="size-4" />
                                </div>

                                <div class="flex flex-col truncate w-full space-y-0.5">
                                    <span
                                        class="truncate font-mono text-[11px] font-bold text-neutral-800 dark:text-neutral-200"
                                        :title="item.file.name">
                                        {{ item.file.name }}
                                    </span>

                                    <div
                                        class="flex flex-wrap items-center gap-x-3 gap-y-0.5 text-[10px] text-neutral-400">
                                        <span
                                            class="flex items-center gap-1 font-semibold text-neutral-500 dark:text-neutral-400">
                                            <User class="size-3 text-neutral-400" /> DNI: {{ item.dni }}
                                        </span>
                                        <span
                                            class="flex items-center gap-1 font-semibold text-neutral-500 dark:text-neutral-400">
                                            <Calendar class="size-3 text-neutral-400" /> Período: {{ item.mes }} - {{
                                                item.anio }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                <span v-if="item.yaExiste"
                                    class="text-[9px] font-bold tracking-wider uppercase px-2 py-0.5 rounded-md bg-red-100 dark:bg-red-950/60 text-red-700 dark:text-red-400 border border-red-200/50 dark:border-red-900/30 whitespace-nowrap">
                                    Ya cargado
                                </span>
                                <Button size="icon" variant="ghost" @click.stop="removerArchivo(idx)"
                                    class="size-7 text-neutral-400 hover:text-rose-600 dark:hover:text-rose-400 rounded-full transition-colors">
                                    ✕
                                </Button>
                            </div>
                        </div>
                    </div>
                </div>
            </CardContent>
        </Card>

    </div>
</template>