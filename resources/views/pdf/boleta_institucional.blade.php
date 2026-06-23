<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>R08: Trabajador — Datos de boleta de pago</title>
    <style>
        /* Definición rígida de la hoja A4 */
        @page {
            margin: 20px 20px 40px 20px;
            /* 🚀 Añadimos más margen abajo (40px) para que la tabla nunca pise al footer */
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 8.5px;
            color: #000000;
            margin: 0;
            padding: 0;
        }

        /* 🎯 EL ANCLA DEL FOOTER COMPATIBLE CON DOMPDF */
        .footer-legal {
            position: fixed;
            bottom: -20px;
            /* Se apoya directamente en el límite del margen inferior de la página */
            left: 0px;
            right: 0px;
            height: 20px;
            text-align: center;
            font-size: 7.5px;
            color: #777777;
            font-style: italic;
            border-top: 1px solid #dddddd;
            padding-top: 5px;
        }

        /* Control total del ancho de las tablas para que entren en una sola hoja ===margin-top: 15px;*/
        .table-plame {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 5px;
        }

        .table-plame td {
            border: 1px solid #444444;
            padding: 2px 4px;
            height: 14px;
            vertical-align: middle;
            word-wrap: break-word;
        }

        /* Clases de alineación nativa */
        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }

        /* Bloque inferior de Firmas */
        .firmas-table {
            width: 100%;
            margin-top: 15px;
            border-collapse: collapse;
        }

        .firmas-table td {
            width: 50%;
            border: none;
            text-align: center;
            vertical-align: top;
        }

        .img-sello {
            height: 155px;
            width: auto;
            display: block;
            margin: 0 auto;
        }

        .img-firma-docente {
            height: 100px;
            width: auto;
            display: block;
            margin: 10px auto 5px auto;
        }

        /* 🎯 CLASES DE COMPACTACIÓN EXTRAORDINARIA PARA FILAS VACÍAS */
        .row-spacer td {
            height: 16px !important;
            /* Forzamos un grosor mínimo de bloque espaciador */
            border: none !important;
            /* Eliminamos los bordes negros sueltos para que no queden cuadros huérfanos */
            background-color: transparent !important;
            padding: 0 !important;
            font-size: 1px !important;
            line-height: 1 !important;
        }

        /* Estilo premium específico para el bloque de RUC, Empleador y Periodo al ras */
        .header-info-style {
            font-weight: bold !important;
            font-size: 9px !important;
            padding-left: 8px !important;
        }
    </style>
</head>

<body>

    <div style="text-align: right; font-size: 8px; font-style: italic; color: #555555; margin-bottom: 10px;">
        Autor: SUNAT | Creado: {{ \Carbon\Carbon::parse($boleta->created_at)->format('d/m/Y H:i') }}
    </div>

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
        <tr>
            <td style="width: 20%; border: none; vertical-align: middle; text-align: left;">
                <img src="{{ public_path('img/Andino_logo.jpg') }}" style="height: 65px; width: auto;"浏览
                    onerror="this.style.display='none'">
            </td>

            <td style="width: 60%; border: none; text-align: center; vertical-align: middle;">
                <h1
                    style="font-size: 18px; font-weight: bold; text-transform: uppercase; margin: 0; padding: 0; letter-spacing: 0.5px;">
                    Boleta de Pago
                </h1>
                <div style="font-size: 11px; font-weight: bold; margin-top: 3px; color: #111111;">
                    {{ $boleta->mes }} {{ $boleta->anio }}
                </div>
                <div style="font-size: 8.5px; font-style: italic; color: #444444; margin-top: 2px;">
                    PDT Planilla Electrónica - PLAME
                </div>
            </td>

            <td style="width: 20%; border: none;"></td>
        </tr>
    </table>

    <table class="table-plame">
        <tbody>
            @foreach ($matrix as $rowIndex => $rowObj)
                @if ($rowIndex >= 6 && $rowIndex <= 41)
                    <tr class="@if ($rowObj['is_spacer']) row-spacer @endif">
                        @foreach ($rowObj['cells'] as $colIndex => $cell)
                            <td class="text-{{ $cell['align'] }} @if ($cell['is_numeric']) font-bold @endif @if ($cell['is_header_info']) header-info-style @endif"
                                style="background-color: {{ $cell['bg_color'] }};"
                                @if ($cell['colspan'] > 1) colspan="{{ $cell['colspan'] }}" @endif
                                @if ($cell['rowspan'] > 1) rowspan="{{ $cell['rowspan'] }}" @endif>
                                {{-- Si es un espaciador, no pintamos nada para que DomPDF colapse la celda perfectamente --}}
                                @if (!$rowObj['is_spacer'])
                                    {{ $cell['value'] }}
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <table class="firmas-table">
        <tr>
            <td>
                <div style="min-height: 135px; padding-top: 5px;">
                    <img src="{{ storage_path('app/private/firmas/sello-firma-boletas.jpg') }}" class="img-sello"
                        onerror="this.style.display='none'" />
                </div>
            </td>

            <td>
                <div style="min-height: 95px;">
                    @if ($boleta->status === 'Firmada' && $boleta->user->signature_path)
                        <img src="{{ storage_path('app/private/' . $boleta->user->signature_path) }}"
                            class="img-firma-docente" />
                        <div style="font-size: 7px; color: #555555; font-family: monospace;">Huella Digital Verificada
                        </div>
                    @else
                        <div
                            style="margin-top: 25px; font-size: 9.5px; color: #cc3333; font-weight: bold; font-style: italic; border: 1px solid #ffcccc; background-color: #fff5f5; padding: 4px 8px; display: inline-block;">
                            Pendiente de Firma Digital
                        </div>
                    @endif
                </div>

                <div
                    style="font-weight: bold; text-transform: uppercase; font-size: 9px; margin-top: 5px; color: #111111;">
                    {{ $boleta->user->paternal_surname }} {{ $boleta->user->maternal_surname }},
                    {{ $boleta->user->first_name }}
                </div>
                <div style="font-size: 8px; color: #444444; font-family: monospace; margin-top: 1px;">
                    DNI: {{ $boleta->user->dni }}
                </div>
            </td>
        </tr>
    </table>

    <div class="footer-legal">
        PDF generado por el sistema institucional | Basado en PDT Planilla Electrónica - PLAME SUNAT.
    </div>

</body>

</html>
