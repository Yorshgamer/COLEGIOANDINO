<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>R08: Trabajador — Datos de boleta de pago</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print {
                display: none;
            }

            body {
                background-color: white;
                color: black;
                padding: 0;
            }

            .print-border {
                border: 1px solid #000000 !important;
            }

            .print-bg {
                background-color: #f3f4f6 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        /* Forzamos bordes colapsados limpios estilo Excel */
        .table-plame th,
        .table-plame td {
            border: 1px solid #d1d5db;
        }

        @media print {

            .table-plame th,
            .table-plame td {
                border: 1px solid #000000;
            }
        }
    </style>
</head>

<body class="bg-neutral-100 text-neutral-900 font-sans p-4 antialiased selection:bg-neutral-200">
    <div
        class="max-w-5xl mx-auto bg-white border border-neutral-300 p-6 rounded-xl shadow-xs print-border print:p-0 print:rounded-none print:shadow-none">

        <div class="flex justify-between items-start border-b border-neutral-300 pb-2">
            <div>
                <h1 class="text-sm font-black tracking-tight text-neutral-950 uppercase">R08: Trabajador – Datos de
                    boleta de pago</h1>
                <p class="text-[10px] text-neutral-500 font-medium">(Contiene datos mínimos de una Boleta de Pago)</p>
            </div>
            <div class="text-right text-[10px] font-mono text-neutral-500">
                <p>{{ \Carbon\Carbon::parse($boleta->created_at)->format('d/m/Y') }}</p>
                <p>{{ \Carbon\Carbon::parse($boleta->created_at)->format('H:i:s') }}</p>
            </div>
        </div>

        <div class="mt-3 text-xs space-y-0.5 border-b border-neutral-300 pb-3">
            <p><span class="font-bold text-neutral-700">RUC :</span> <span
                    class="font-mono">{{ $datosBoleta['ruc'] }}</span></p>
            <p><span class="font-bold text-neutral-700">Empleador :</span> <span
                    class="uppercase font-semibold">{{ $datosBoleta['colegio'] }}</span></p>
            <p><span class="font-bold text-neutral-700">Periodo :</span> <span
                    class="font-mono font-bold">{{ \Carbon\Carbon::parse($boleta->anio . '-' . $boleta->mes . '-01')->format('m/Y') }}</span>
            </p>
            <p><span class="font-bold text-neutral-700">PDT Planilla Electrónica - PLAME</span></p>
        </div>

        <table class="w-full text-[11px] table-plame mt-4 border-collapse">
            <thead>
                <tr class="bg-neutral-50 text-neutral-700 print-bg">
                    <th colspan="2" class="px-2 py-1 font-bold text-left w-[25%]">Documento de Identidad</th>
                    <th rowspan="2" class="px-2 py-1 font-bold text-center w-[50%]">Nombre y Apellidos</th>
                    <th rowspan="2" class="px-2 py-1 font-bold text-center w-[25%]">Situación</th>
                </tr>
                <tr class="bg-neutral-50 text-neutral-600 print-bg">
                    <th class="px-2 py-0.5 font-medium text-center border-t">Tipo</th>
                    <th class="px-2 py-0.5 font-medium text-center border-t">Número</th>
                </tr>
            </thead>
            <tbody>
                <tr class="text-center font-medium text-neutral-900">
                    <td class="px-2 py-1 font-mono">DNI</td>
                    <td class="px-2 py-1 font-mono">{{ $datosBoleta['empleado']->dni }}</td>
                    <td class="px-2 py-1 uppercase font-bold text-left">
                        {{ $datosBoleta['empleado']->paternal_surname }}
                        {{ $datosBoleta['empleado']->maternal_surname }}, {{ $datosBoleta['empleado']->first_name }}
                    </td>
                    <td class="px-2 py-1 text-xs font-semibold text-green-700">ACTIVO O SUBSIDIADO</td>
                </tr>
            </tbody>
        </table>

        <table class="w-full text-[11px] table-plame border-t-0 border-collapse">
            <thead>
                <tr class="bg-neutral-50 text-neutral-700 print-bg">
                    <th class="px-2 py-1 font-bold text-center w-[25%]">Fecha de Ingreso</th>
                    <th class="px-2 py-1 font-bold text-center w-[25%]">Tipo de Trabajador</th>
                    <th class="px-2 py-1 font-bold text-center w-[25%]">Régimen Pensionario</th>
                    <th class="px-2 py-1 font-bold text-center w-[25%]">CUSPP</th>
                </tr>
            </thead>
            <tbody>
                <tr class="text-center font-medium text-neutral-900">
                    <td class="px-2 py-1 font-mono">{{ $datosBoleta['fecha_ingreso'] }}</td>
                    <td class="px-2 py-1 uppercase">EMPLEADO</td>
                    <td class="px-2 py-1 font-semibold uppercase">
                        {{ $datosBoleta['empleado']->regimen_pensionario ?? 'SPP PRIMA' }}</td>
                    <td class="px-2 py-1 font-mono">{{ $datosBoleta['cuspp'] }}</td>
                </tr>
            </tbody>
        </table>

        <table class="w-full text-[11px] table-plame border-t-0 border-collapse">
            <thead>
                <tr class="bg-neutral-50 text-neutral-700 print-bg">
                    <th rowspan="2" class="px-2 py-1 font-bold text-center w-[12%]">Días Laborados</th>
                    <th rowspan="2" class="px-2 py-1 font-bold text-center w-[12%]">Días No Laborados</th>
                    <th rowspan="2" class="px-2 py-1 font-bold text-center w-[12%]">Días subsidiados</th>
                    <th rowspan="2" class="px-2 py-1 font-bold text-center w-[14%]">Condición</th>
                    <th colspan="2" class="px-2 py-0.5 font-bold text-center w-[25%]">Jornada Ordinaria</th>
                    <th colspan="2" class="px-2 py-0.5 font-bold text-center w-[25%]">Sobretiempo</th>
                </tr>
                <tr class="bg-neutral-50 text-neutral-600 print-bg">
                    <th class="px-2 py-0.5 font-medium text-center">Total</th>
                    <th class="px-2 py-0.5 font-medium text-center">Minutos</th>
                    <th class="px-2 py-0.5 font-medium text-center">Total</th>
                    <th class="px-2 py-0.5 font-medium text-center">Minutos</th>
                </tr>
            </thead>
            <tbody>
                <tr class="text-center font-medium text-neutral-900">
                    <td class="px-2 py-1 font-mono">{{ $datosBoleta['dias_laborados'] }}</td>
                    <td class="px-2 py-1 font-mono">0</td>
                    <td class="px-2 py-1 font-mono">0</td>
                    <td class="px-2 py-1 text-xs">Domiciliad.</td>
                    <td class="px-2 py-1 font-mono">{{ $datosBoleta['total_horas'] }}</td>
                    <td class="px-2 py-1 font-mono"></td>
                    <td class="px-2 py-1 font-mono"></td>
                    <td class="px-2 py-1 font-mono"></td>
                </tr>
            </tbody>
        </table>

        <table class="w-full text-[11px] table-plame border-t-0 border-collapse">
            <thead>
                <tr class="bg-neutral-50 text-neutral-700 print-bg">
                    <th colspan="3" class="px-2 py-1 font-bold text-center w-[50%]">Motivo de Suspensión de Labores
                    </th>
                    <th rowspan="2" class="px-2 py-1 font-bold text-center w-[50%]">Otros empleadores por Rentas de
                        5ta categoría</th>
                </tr>
                <tr class="bg-neutral-50 text-neutral-600 print-bg">
                    <th class="px-2 py-0.5 font-medium text-left w-[15%]">Tipo</th>
                    <th class="px-2 py-0.5 font-medium text-left w-[25%]">Motivo</th>
                    <th class="px-2 py-0.5 font-medium text-center w-[10%]">N.º Días</th>
                </tr>
            </thead>
            <tbody>
                <tr class="text-center font-medium text-neutral-900">
                    <td class="px-2 py-1 text-left"></td>
                    <td class="px-2 py-1 text-left"></td>
                    <td class="px-2 py-1"></td>
                    <td class="px-2 py-1 text-neutral-500 font-mono text-xs">No tiene</td>
                </tr>
            </tbody>
        </table>

        <table class="w-full text-[11px] table-plame border-t-0 border-collapse mt-4">
            <thead class="bg-neutral-100 text-neutral-800 print-bg font-bold">
                <tr class="text-center">
                    <th class="px-3 py-1.5 text-left w-[12%]">Código</th>
                    <th class="px-3 py-1.5 text-left w-[48%]">Conceptos</th>
                    <th class="px-3 py-1.5 text-right w-[13%]">Ingresos S/.</th>
                    <th class="px-3 py-1.5 text-right w-[13%]">Descuentos S/.</th>
                    <th class="px-3 py-1.5 text-right w-[14%]">Neto S/.</th>
                </tr>
            </thead>
            <tbody class="font-medium text-neutral-700 divide-y divide-neutral-200">

                <tr class="bg-blue-50/40 font-bold print-bg">
                    <td colspan="5" class="px-3 py-0.5 text-blue-900 text-[10px]">Ingresos</td>
                </tr>
                @foreach ($datosBoleta['ingresos'] as $ingreso)
                    <tr>
                        <td class="px-3 py-1 font-mono text-neutral-400">{{ $ingreso['codigo'] }}</td>
                        <td class="px-3 py-1 text-neutral-900 uppercase">{{ $ingreso['concepto'] }}</td>
                        <td class="px-3 py-1 text-right tabular-nums font-semibold text-neutral-900">
                            {{ number_format($ingreso['monto'], 2) }}</td>
                        <td class="px-3 py-1 text-right text-neutral-300">—</td>
                        <td class="px-3 py-1 text-right text-neutral-300">—</td>
                    </tr>
                @endforeach

                <tr class="bg-amber-50/40 font-bold print-bg">
                    <td colspan="5" class="px-3 py-0.5 text-amber-900 text-[10px]">Descuentos</td>
                </tr>
                @foreach ($datosBoleta['descuentos'] as $descuento)
                    <tr>
                        <td class="px-3 py-1 font-mono text-neutral-400">{{ $descuento['codigo'] }}</td>
                        <td class="px-3 py-1 text-neutral-900 uppercase">{{ $descuento['concepto'] }}</td>
                        <td class="px-3 py-1 text-right text-neutral-300">—</td>
                        <td class="px-3 py-1 text-right tabular-nums font-semibold text-neutral-900">
                            {{ number_format($descuento['monto'], 2) }}</td>
                        <td class="px-3 py-1 text-right text-neutral-300">—</td>
                    </tr>
                @endforeach

                <tr class="bg-neutral-50 font-bold print-bg">
                    <td colspan="5" class="px-3 py-0.5 text-neutral-800 text-[10px]">Aportes del Trabajador</td>
                </tr>
                @foreach ($datosBoleta['aportes'] as $aporte)
                    <tr>
                        <td class="px-3 py-1 font-mono text-neutral-400">{{ $aporte['codigo'] }}</td>
                        <td class="px-3 py-1 text-neutral-500 uppercase">{{ $aporte['concepto'] }}</td>
                        <td class="px-3 py-1 text-right text-neutral-300">—</td>
                        <td class="px-3 py-1 text-right tabular-nums text-neutral-600">
                            {{ number_format($aporte['monto'], 2) }}</td>
                        <td class="px-3 py-1 text-right text-neutral-300">—</td>
                    </tr>
                @endforeach

                <tr class="bg-neutral-900 text-white font-black text-xs print-bg">
                    <td colspan="2" class="px-3 py-2 text-left uppercase tracking-wider">Neto a Pagar</td>
                    <td class="px-3 py-2 text-right tabular-nums">—</td>
                    <td class="px-3 py-2 text-right tabular-nums">—</td>
                    <td class="px-3 py-2 text-right tabular-nums text-sm">S/.
                        {{ number_format($datosBoleta['neto_pagar'], 2) }}</td>
                </tr>
            </tbody>
        </table>

        <table class="w-full text-[11px] table-plame border-t-0 border-collapse mt-2">
            <thead class="bg-neutral-50 text-neutral-700 print-bg font-bold">
                <tr class="text-left">
                    <th colspan="5" class="px-3 py-1 text-[10px]">Aportes de Empleador</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-3 py-1 font-mono text-neutral-400 w-[12%]">0804</td>
                    <td class="px-3 py-1 text-neutral-500 uppercase w-[48%]">ESSALUD (REGULAR CBSSP AGRAR/AC) TRAB</td>
                    <td class="px-3 py-1 text-right text-neutral-300 w-[13%]">—</td>
                    <td class="px-3 py-1 text-right text-neutral-300 w-[13%]">—</td>
                    <td class="px-3 py-1 text-right tabular-nums font-semibold text-neutral-600 w-[14%]">135.00</td>
                </tr>
            </tbody>
        </table>

        <div class="grid grid-cols-2 gap-16 mt-4 pb-4 relative">

            <div class="flex items-start justify-center h-48 relative overflow-visible">
                <div class="select-none flex items-center justify-center origin-top transform scale-125 pt-2">
                    <img src="/administracion/firma/sello" alt="Firma y Sello Oficial Empleador"
                        class="h-40 w-auto object-contain block" onerror="this.style.display='none'" />
                </div>
            </div>

            <div class="flex flex-col items-center justify-start text-center h-48 relative pt-4">
                @if ($boleta->status === 'Firmada')
                    <div class="h-28 flex items-center justify-center mb-2">
                        <img src="/boletas/{{ $boleta->id }}/trabajador-firma" alt="Firma Digital Docente"
                            class="h-16 object-contain bg-white" />
                    </div>
                    <div class="absolute bottom-1 right-2 text-[8px] font-mono text-neutral-400 no-print">
                        Huella DNI: {{ $datosBoleta['empleado']->dni }}
                    </div>
                @else
                    <div class="h-28 flex items-center justify-center mb-2">
                        <div
                            class="text-[10px] italic font-bold text-red-500 bg-red-50 px-2.5 py-0.5 rounded-md border border-red-200/40 no-print">
                            Pendiente de Firma Digital
                        </div>
                    </div>
                @endif

                <span class="text-[10px] uppercase font-bold text-neutral-800 tracking-wider block">
                    {{ $datosBoleta['empleado']->paternal_surname }} {{ $datosBoleta['empleado']->maternal_surname }},
                    {{ $datosBoleta['empleado']->first_name }}
                </span>
                <span class="text-[9px] text-neutral-400 font-mono mt-0.5 block">
                    DNI: {{ $datosBoleta['empleado']->dni }}
                </span>
            </div>
        </div>
    </div>
</body>

</html>
