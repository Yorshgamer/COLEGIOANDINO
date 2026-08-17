<x-mail::message>

<?php
    // 🎨 Recuperamos los colores reales configurados en el backend
    $colorPrimario   = config('mail.colors.primary', '#af0c27');
    $colorSecundario = config('mail.colors.secondary', '#212e58');
?>

{{-- ══════════════════════════════════════════
     ENCABEZADO INSTITUCIONAL CON LOGO ALINEADO
     ══════════════════════════════════════════ --}}
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 15px;">
    <tr>
        <td align="left" style="vertical-align: middle;">
            @if (! empty($greeting))
                <h1 style="font-size: 20px; font-weight: 800; color: {{ $colorSecundario }}; margin: 0; text-transform: uppercase; letter-spacing: -0.02em; font-family: Arial, sans-serif;">
                    {{ $greeting }}
                </h1>
            @else
                <h1 style="font-size: 20px; font-weight: 800; color: {{ $colorSecundario }}; margin: 0; text-transform: uppercase; font-family: Arial, sans-serif;">
                    NOTIFICACIÓN INSTITUCIONAL
                </h1>
            @endif
        </td>
        <td align="right" style="width: 80px; vertical-align: middle;">
            <img src="{{ asset('img/Andino_logo.jpg') }}" alt="Logo Colegio Andino" style="width: 70px; height: auto; display: block; border-radius: 6px;" />
        </td>
    </tr>
</table>

{{-- Línea divisoria en Rojo Andino --}}
<div style="border-top: 3px solid {{ $colorPrimario }}; margin-top: 5px; margin-bottom: 25px;"></div>

{{-- ══════════════════════════════════════════
     CUERPO DEL MENSAJE (Permitiendo HTML Directo)
     ══════════════════════════════════════════ --}}
<div style="color: #333333; font-size: 14px; line-height: 1.6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">
@foreach ($introLines as $line)
    <p style="margin-bottom: 1em;">{!! $line !!}</p>
@endforeach
</div>

{{-- ══════════════════════════════════════════
     BOTÓN DE ACCIÓN
     ══════════════════════════════════════════ --}}
@isset($actionText)
<div style="margin-top: 25px; margin-bottom: 25px; text-align: center;">
    <a href="{{ $actionUrl }}" style="background-color: {{ $colorPrimario }}; color: #ffffff; padding: 12px 24px; text-decoration: none; font-size: 13px; font-weight: bold; border-radius: 8px; display: inline-block; font-family: Arial, sans-serif; box-shadow: 0 2px 4px rgba(175,12,39,0.2);">
        {{ $actionText }}
    </a>
</div>
@endisset

{{-- ══════════════════════════════════════════
     LÍNEAS DE CIERRE
     ══════════════════════════════════════════ --}}
<div style="color: #333333; font-size: 14px; line-height: 1.6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">
@foreach ($outroLines as $line)
    <p style="margin-bottom: 1em;">{!! $line !!}</p>
@endforeach
</div>

<div style="border-top: 1px solid #e5e5e5; margin-top: 25px; margin-bottom: 20px;"></div>

{{-- ══════════════════════════════════════════
     DESPEDIDA INSTITUCIONAL (Centrada y Limpia)
     ══════════════════════════════════════════ --}}
<div style="color: #525252; font-size: 13px; line-height: 1.6; font-family: Arial, sans-serif; text-align: center; width: 100%;">
@if (! empty($salutation))
    {!! nl2br($salutation) !!}
@else
    Atentamente,<br>
    <strong>Colegio Particular Andino</strong>
@endif
</div>

{{-- ══════════════════════════════════════════
     SUBCOPIA DE RESPALDO TRADUCIDA
     ══════════════════════════════════════════ --}}
@isset($actionText)
<x-slot:subcopy>
Si presenta inconvenientes con el botón institucional **"{{ $actionText }}"**, copie y pegue la siguiente dirección URL directamente en la barra de su navegador:

<span class="break-all" style="color: #1d4ed8;">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
</x-slot:subcopy>
@endisset

</x-mail::message>