<script setup lang="ts">
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { ArrowLeft, Printer, Download } from '@lucide/vue';

interface DetalleMonto {
    codigo: string;
    descripcion: string;
    monto: number;
}

interface BoletaData {
    id: number;
    type: string;
    mes: string;
    anio: number;
    status: string;
    signed_at: string | null;
    observation_message: string | null;
    is_xml: boolean;
    pdf_url: string;
    empleado: {
        nombre_completo: string;
        dni: string;
        cargo?: string;
        regimen?: string;
        fecha_ingreso?: string;
        tipo_trabajador?: string;
        cuspp?: string;
        dias_laborados?: number;
        dias_no_laborados?: number;
        dias_subsidiados?: number;
        condicion?: string;
        horas_ordinarias?: number;
        minutos_ordinarios?: number;
        horas_sobretiempo?: number;
        minutos_sobretiempo?: number;
        motivo_suspension_tipo?: string;
        motivo_suspension_desc?: string;
        motivo_suspension_dias?: number;
        otros_empleadores?: string;
    };
    conceptos?: {
        ingresos: DetalleMonto[];
        descuentos: DetalleMonto[];
        aportes: DetalleMonto[];
        totales: {
            ingresos: number;
            descuentos: number;
            neto: number;
        };
    };
}

const props = defineProps<{
    boleta: BoletaData;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Documentos Laborales', href: '/boletas' },
            { title: 'Visualización', href: '#' },
        ],
    },
});

const formatSoles = (monto: number | undefined) => {
    if (monto === undefined || monto === null) return '';
    return new Intl.NumberFormat('es-PE', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(monto);
};

const triggerPrint = () => {
    window.print();
};
</script>

<template>
    <Head :title="`Boleta de Pago - ${boleta.empleado.nombre_completo}`" />

    <div class="flex h-full flex-1 flex-col gap-4 p-6 max-w-5xl mx-auto printable-area">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b pb-5 print:hidden">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100">
                    Visor de Documento Laboral
                </h1>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">
                    Visualización fidedigna del reporte estructurado PDT PLAME SUNAT.
                </p>
            </div>
            
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <Link href="/boletas" class="inline-flex items-center justify-center rounded-md border border-neutral-300 bg-white px-3 py-2 text-xs font-medium text-neutral-700 shadow-sm hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300">
                    <ArrowLeft class="size-3.5 mr-1.5" /> Volver
                </Link>
                <Button variant="outline" size="sm" @click="triggerPrint" class="text-xs font-bold bg-white dark:bg-neutral-900">
                    <Printer class="size-3.5 mr-1.5" /> Imprimir Boleta
                </Button>
                <a :href="boleta.pdf_url" download class="inline-flex items-center justify-center rounded-md bg-neutral-900 px-3 py-2 text-xs font-medium text-neutral-50 shadow-sm hover:bg-neutral-800 dark:bg-neutral-50 dark:text-neutral-900 dark:hover:bg-neutral-50/90">
                    <Download class="size-3.5 mr-1.5" /> Exportar PDF
                </a>
            </div>
        </div>

        <div class="bg-white text-black p-8 border border-neutral-300 shadow-lg mx-auto w-full max-w-[21cm] font-sans text-[11px] leading-tight print:border-none print:shadow-none print:p-0">
            
            <div class="grid grid-cols-3 items-center mb-4">
                <div class="flex flex-col items-start space-y-1">
                    <div class="flex items-center gap-1.5">
                        <span class="font-bold text-xs uppercase tracking-tight text-neutral-800">Colegio Andino</span>
                    </div>
                    <span class="text-[9px] text-neutral-500 font-mono">Autor: SUNAT | Creado: 2026</span>
                </div>
                <div class="text-center">
                    <h2 class="text-sm font-bold uppercase tracking-wide">Boleta de Pago</h2>
                    <p class="font-semibold text-xs mt-0.5">{{ boleta.mes }} {{ boleta.anio }}</p>
                    <p class="text-[9px] text-neutral-500 italic">PDT Planilla Electrónica - PLAME</p>
                </div>
                <div class="text-right text-[9px] text-neutral-400 font-mono">
                    </div>
            </div>

            <div class="border border-neutral-400 mb-3 bg-neutral-50/50">
                <div class="p-1.5 border-b border-neutral-300"><span class="font-bold">RUC:</span> 20161572366</div>
                <div class="p-1.5 border-b border-neutral-300"><span class="font-bold">Empleador:</span> COLEGIO PARTICULAR ANDINO</div>
                <div class="p-1.5"><span class="font-bold">Periodo:</span> {{ boleta.mes }} {{ boleta.anio }}</div>
            </div>

            <div class="grid grid-cols-4 border border-neutral-400 text-center divide-x divide-neutral-400 mb-3">
                <div class="col-span-1 divide-y divide-neutral-400">
                    <div class="bg-neutral-50 p-1 font-bold text-[10px]">Documento de Identidad</div>
                    <div class="grid grid-cols-2 divide-x divide-neutral-400 text-[10px]">
                        <div class="p-1 bg-neutral-50/50">Tipo</div>
                        <div class="p-1 bg-neutral-50/50">Número</div>
                    </div>
                    <div class="grid grid-cols-2 divide-x divide-neutral-400 font-mono">
                        <div class="p-1">DNI</div>
                        <div class="p-1 font-bold">{{ boleta.empleado.dni }}</div>
                    </div>
                </div>
                <div class="col-span-2 divide-y divide-neutral-400">
                    <div class="bg-neutral-50 p-1 font-bold text-[10px]">Nombre y Apellidos</div>
                    <div class="p-2 font-bold text-xs flex items-center justify-center h-full uppercase">
                        {{ boleta.empleado.nombre_completo }}
                    </div>
                </div>
                <div class="col-span-1 divide-y divide-neutral-400">
                    <div class="bg-neutral-50 p-1 font-bold text-[10px]">Situación</div>
                    <div class="p-2 font-bold flex items-center justify-center h-full text-green-700 text-[10px]">
                        {{ boleta.empleado.condicion ?? 'ACTIVO O SUBSIDIADO' }}
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-4 border border-neutral-400 text-center divide-x divide-neutral-400 text-[10px] mb-3">
                <div class="divide-y divide-neutral-400">
                    <div class="bg-neutral-50 p-1 font-bold">Fecha de Ingreso</div>
                    <div class="p-1 font-mono font-semibold">{{ boleta.empleado.fecha_ingreso ?? '01/04/2014' }}</div>
                </div>
                <div class="divide-y divide-neutral-400">
                    <div class="bg-neutral-50 p-1 font-bold">Tipo de Trabajador</div>
                    <div class="p-1 uppercase">{{ boleta.empleado.tipo_trabajador ?? 'EMPLEADO' }}</div>
                </div>
                <div class="divide-y divide-neutral-400">
                    <div class="bg-neutral-50 p-1 font-bold">Régimen Pensionario</div>
                    <div class="p-1 uppercase font-semibold">{{ boleta.empleado.regimen ?? 'DL 19990 - SIST NAC DE PENS' }}</div>
                </div>
                <div class="divide-y divide-neutral-400">
                    <div class="bg-neutral-50 p-1 font-bold">CUSPP</div>
                    <div class="p-1 font-mono text-neutral-500">{{ boleta.empleado.cuspp ?? 'No tiene' }}</div>
                </div>
            </div>

            <div class="grid grid-cols-6 border border-neutral-400 text-center divide-x divide-neutral-400 text-[10px] mb-3">
                <div class="divide-y divide-neutral-400">
                    <div class="bg-neutral-50 p-1 font-bold">Días Laborados</div>
                    <div class="p-1 font-mono font-bold text-neutral-800">{{ boleta.empleado.dias_laborados ?? 24 }}</div>
                </div>
                <div class="divide-y divide-neutral-400">
                    <div class="bg-neutral-50 p-1 font-bold">Días No Laborados</div>
                    <div class="p-1 font-mono text-rose-600">{{ boleta.empleado.dias_no_laborados ?? 0 }}</div>
                </div>
                <div class="divide-y divide-neutral-400">
                    <div class="bg-neutral-50 p-1 font-bold">Días Subsidiados</div>
                    <div class="p-1 font-mono">{{ boleta.empleado.dias_subsidiados ?? 0 }}</div>
                </div>
                <div class="divide-y divide-neutral-400">
                    <div class="bg-neutral-50 p-1 font-bold">Condición</div>
                    <div class="p-1 uppercase">{{ boleta.empleado.condicion ?? 'Domiciliado' }}</div>
                </div>
                <div class="col-span-1 divide-y divide-neutral-400">
                    <div class="bg-neutral-50 p-1 font-bold">Jornada Ordinaria</div>
                    <div class="grid grid-cols-2 divide-x divide-neutral-400 text-[9px] bg-neutral-50/30">
                        <div>Total Horas</div>
                        <div>Minutos</div>
                    </div>
                    <div class="grid grid-cols-2 divide-x divide-neutral-400 font-mono">
                        <div>{{ boleta.empleado.horas_ordinarias ?? 160 }}</div>
                        <div>{{ boleta.empleado.minutos_ordinarios ?? 0 }}</div>
                    </div>
                </div>
                <div class="col-span-1 divide-y divide-neutral-400">
                    <div class="bg-neutral-50 p-1 font-bold">Sobretiempo</div>
                    <div class="grid grid-cols-2 divide-x divide-neutral-400 text-[9px] bg-neutral-50/30">
                        <div>Total Horas</div>
                        <div>Minutos</div>
                    </div>
                    <div class="grid grid-cols-2 divide-x divide-neutral-400 font-mono text-neutral-500">
                        <div>{{ boleta.empleado.horas_sobretiempo ?? 0 }}</div>
                        <div>{{ boleta.empleado.minutos_sobretiempo ?? 0 }}</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-4 border border-neutral-400 text-center divide-x divide-neutral-400 text-[10px] mb-4">
                <div class="col-span-3 divide-y divide-neutral-400">
                    <div class="bg-neutral-50 p-1 font-bold">Motivo de Suspensión de Labores</div>
                    <div class="grid grid-cols-4 divide-x divide-neutral-400 text-[9px] bg-neutral-50/30 font-semibold">
                        <div class="col-span-1">Tipo</div>
                        <div class="col-span-2">Motivo</div>
                        <div class="col-span-1">N.° Días</div>
                    </div>
                    <div class="grid grid-cols-4 divide-x divide-neutral-400 text-neutral-700">
                        <div class="col-span-1 font-mono p-1">{{ boleta.empleado.motivo_suspension_tipo ?? '—' }}</div>
                        <div class="col-span-2 text-left px-2 p-1 truncate">{{ boleta.empleado.motivo_suspension_desc ?? 'Sin suspensiones en este período' }}</div>
                        <div class="col-span-1 font-mono p-1">{{ boleta.empleado.motivo_suspension_dias ?? 0 }}</div>
                    </div>
                </div>
                <div class="col-span-1 divide-y divide-neutral-400">
                    <div class="bg-neutral-50 p-1 font-bold">Otros empleadores por Rentas de 5ta</div>
                    <div class="p-3 text-neutral-500 font-medium">
                        {{ boleta.empleado.otros_empleadores ?? 'No tiene' }}
                    </div>
                </div>
            </div>

            <div class="border border-neutral-400 mb-6 font-mono text-[10px]">
                <div class="grid grid-cols-12 bg-neutral-100 font-sans text-[10px] font-bold uppercase tracking-tight text-center border-b border-neutral-400 divide-x divide-neutral-400">
                    <div class="col-span-2 p-1.5">Código</div>
                    <div class="col-span-4 p-1.5 text-left pl-3">Conceptos</div>
                    <div class="col-span-2 p-1.5">Ingresos S/.</div>
                    <div class="col-span-2 p-1.5">Descuentos S/.</div>
                    <div class="col-span-2 p-1.5">Neto S/.</div>
                </div>

                <div v-if="boleta.conceptos" class="divide-y divide-neutral-200 bg-background min-h-[220px]">
                    
                    <div class="bg-neutral-50/30 font-sans px-2 py-0.5 font-bold text-[9px] tracking-tight text-blue-800">Ingresos</div>
                    <div v-for="ing in boleta.conceptos.ingresos" :key="ing.codigo" class="grid grid-cols-12 divide-x divide-neutral-200 text-neutral-800">
                        <div class="col-span-2 text-center p-1 text-neutral-400">{{ ing.codigo }}</div>
                        <div class="col-span-4 p-1 pl-3 uppercase tracking-tight text-[9px] font-sans font-medium text-neutral-700">{{ ing.descripcion }}</div>
                        <div class="col-span-2 text-right p-1 pr-3 font-bold text-neutral-900">{{ formatSoles(ing.monto) }}</div>
                        <div class="col-span-2 bg-neutral-50/20"></div>
                        <div class="col-span-2 bg-neutral-50/20"></div>
                    </div>

                    <div class="bg-neutral-50/30 font-sans px-2 py-0.5 font-bold text-[9px] tracking-tight text-rose-800">Descuentos</div>
                    <div v-for="desc in boleta.conceptos.descuentos" :key="desc.codigo" class="grid grid-cols-12 divide-x divide-neutral-200 text-neutral-800">
                        <div class="col-span-2 text-center p-1 text-neutral-400">{{ desc.codigo }}</div>
                        <div class="col-span-4 p-1 pl-3 uppercase tracking-tight text-[9px] font-sans font-medium text-neutral-700">{{ desc.descripcion }}</div>
                        <div class="col-span-2 bg-neutral-50/20"></div>
                        <div class="col-span-2 text-right p-1 pr-3 font-bold text-rose-600">{{ formatSoles(desc.monto) }}</div>
                        <div class="col-span-2 bg-neutral-50/20"></div>
                    </div>

                    <div class="bg-neutral-50/30 font-sans px-2 py-0.5 font-bold text-[9px] tracking-tight text-amber-800">Aportes del Trabajador</div>
                    <div v-for="ap in boleta.conceptos.aportes" :key="ap.codigo" class="grid grid-cols-12 divide-x divide-neutral-200 text-neutral-800">
                        <div class="col-span-2 text-center p-1 text-neutral-400">{{ ap.codigo }}</div>
                        <div class="col-span-4 p-1 pl-3 uppercase tracking-tight text-[9px] font-sans font-medium text-neutral-700">{{ ap.descripcion }}</div>
                        <div class="col-span-2 bg-neutral-50/20"></div>
                        <div class="col-span-2 text-right p-1 pr-3 font-bold text-neutral-700">{{ formatSoles(ap.monto) }}</div>
                        <div class="col-span-2 bg-neutral-50/20"></div>
                    </div>
                </div>

                <div class="grid grid-cols-12 bg-neutral-50 border-t border-neutral-400 font-sans font-bold text-[11px] uppercase tracking-tight divide-x divide-neutral-400">
                    <div class="col-span-10 p-2 text-right pr-4 bg-neutral-100 text-neutral-900">Neto a Pagar S/.</div>
                    <div class="col-span-2 p-2 text-right pr-3 bg-green-50/30 text-green-700 text-sm font-mono tracking-tighter">
                        {{ boleta.conceptos ? formatSoles(boleta.conceptos.totales.neto) : '0.00' }}
                    </div>
                </div>
            </div>

            <div class="border border-neutral-400 font-mono text-[10px] mb-8">
                <div class="grid grid-cols-12 bg-neutral-50 font-sans font-bold uppercase tracking-tight p-1 border-b border-neutral-400 pl-3 text-neutral-500">
                    Aportes de Empleador
                </div>
                <div class="divide-y divide-neutral-200">
                    <div class="grid grid-cols-12 divide-x divide-neutral-200 text-neutral-600">
                        <div class="col-span-2 text-center p-1">0803</div>
                        <div class="col-span-8 p-1 pl-3 font-sans text-[9px]">PÓLIZA DE SEGURO - D. LEG. 688</div>
                        <div class="col-span-2 text-right p-1 pr-3 font-bold text-neutral-800">10.81</div>
                    </div>
                    <div class="grid grid-cols-12 divide-x divide-neutral-200 text-neutral-600">
                        <div class="col-span-2 text-center p-1">0804</div>
                        <div class="col-span-8 p-1 pl-3 font-sans text-[9px]">ESSALUD (REGULAR CBSSP AGRAR/AC) TRAB</div>
                        <div class="col-span-2 text-right p-1 pr-3 font-bold text-neutral-800">133.18</div>
                    </div>
                    <div class="grid grid-cols-12 divide-x divide-neutral-200 text-neutral-600">
                        <div class="col-span-2 text-center p-1">0806</div>
                        <div class="col-span-8 p-1 pl-3 font-sans text-[9px]">ESSALUD - SCTR</div>
                        <div class="col-span-2 text-right p-1 pr-3 font-bold text-neutral-800">9.32</div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <div class="flex flex-col items-center justify-center text-center p-3 border border-neutral-300 rounded-xl bg-neutral-50/20 max-w-[240px] space-y-1">
                    <div class="size-16 rounded-full border border-dashed border-blue-400 flex items-center justify-center italic text-blue-500 text-[8px] tracking-tighter text-center uppercase p-1 p-2 font-bold select-none opacity-80 mix-blend-multiply">
                        Colegio Particular Andino<br>Andean School
                    </div>
                    <p class="font-bold text-neutral-800 text-[10px] mt-1">Thalia Araíza Sánchez Laura</p>
                    <p class="text-neutral-400 text-[9px] tracking-tight">Administradora General</p>
                </div>
            </div>

        </div>
    </div>
</template>

<style>
@media print {
    body {
        background-color: white !important;
        color: black !important;
    }
    .printable-area {
        padding: 0 !important;
        max-width: 100% !important;
    }
}
</style>