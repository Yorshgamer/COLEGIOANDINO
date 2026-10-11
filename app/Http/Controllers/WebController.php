<?php

namespace App\Http\Controllers;

use App\Models\Boletin;
use App\Models\LibroReclamacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Inertia\Response;

class WebController extends Controller
{
    public function home(): Response
    {
        return Inertia::render('Welcome');
    }

    public function presentacion(): Response
    {
        return Inertia::render('Web/Presentacion', [
            'title' => 'Presentación Institucional'
        ]);
    }

    public function iglesiaMetodista(): Response
    {
        return Inertia::render('Web/IglesiaMetodista', [
            'title' => 'Nuestra Historia - Iglesia Metodista'
        ]);
    }

    public function misionVision(): Response
    {
        return Inertia::render('Web/MisionVision', [
            'title' => 'Misión y Visión'
        ]);
    }

    public function historia(): Response
    {
        return Inertia::render('Web/Historia', [
            'title' => 'Nuestra Historia'
        ]);
    }

    public function orientacion(): Response
    {
        return Inertia::render('Web/Orientacion', [
            'title' => 'Orientación'
        ]);
    }
    public function capellania(): Response
    {
        return Inertia::render('Web/Capellania', [
            'title' => 'Capellania'
        ]);
    }

    public function coordinacion(): Response
    {
        return Inertia::render('Web/Coordinacion', [
            'title' => 'Coordinación'
        ]);
    }
    public function psicologia(): Response
    {
        return Inertia::render('Web/Psicologia', [
            'title' => 'Departamento de Psicología'
        ]);
    }

    public function actividades(): Response
    {
        return Inertia::render('Web/Actividades', [
            'title' => 'Actividades'
        ]);
    }

    public function admision(): Response
    {
        return Inertia::render('Web/Admision', [
            'title' => 'Admisión'
        ]);
    }
    public function matricula(): Response
    {
        return Inertia::render('Web/Matricula', [
            'title' => 'Matrícula'
        ]);
    }
    public function contacto(): Response
    {
        return Inertia::render('Web/Contacto', [
            'title' => 'Contacto'
        ]);
    }
    public function servicio(): Response
    {
        return Inertia::render('Web/Servicio', [
            'title' => 'Nuestros Servicios'
        ]);
    }
    public function trabajaNosotros(): Response
    {
        return Inertia::render('Web/TrabajaNosotros', [
            'title' => 'Trabaja con Nosotros'
        ]);
    }
    public function responsabilidadSocial(): Response
    {
        return Inertia::render('Web/ResponsabilidadSocial', [
            'title' => 'Responsabilidad Social'
        ]);
    }
    public function galeria(): Response
    {
        return Inertia::render('Web/Galeria', [
            'title' => 'Galería de Fotos - Colegio Andino'
        ]);
    }
    public function libro(): Response
    {
        return Inertia::render('Web/LibroReclamaciones', [
            'title' => 'Libro de Reclamaciones - Colegio Andino',
            'recaptchaSiteKey' => config('services.recaptcha.site_key'),
        ]);
    }

    public function storeLibro(Request $request)
    {
        $validated = $request->validate([
            'nombres'          => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u'],
            'apellidos'        => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u'],
            'correo'           => ['required', 'email:rfc,dns', 'max:150'],
            'domicilio'        => ['required', 'string', 'max:255'],
            'dni'              => ['required', 'string', 'regex:/^[0-9]{8,12}$/'], // DNI 8 dígitos / CE 9-12 dígitos numéricos
            'telefono'         => ['required', 'string', 'regex:/^[0-9]{7,9}$/'], // Fijo 7 dígitos / Celular 9 dígitos
            'padre_madre'      => ['nullable', 'string', 'max:150', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/u'],
            'bien_contratado'  => ['required', 'in:servicio,producto'],
            'descripcion_bien' => ['required', 'string', 'max:1000'],
            'tipo_reclamo'     => ['required', 'in:reclamo,queja'],
            'detalle_reclamo'  => ['required', 'string', 'max:2000'],
            'politica'         => ['accepted'],
            'recaptcha_token'  => ['required', 'string'],
        ], [
            'nombres.regex'            => 'Los nombres solo deben contener letras.',
            'apellidos.regex'          => 'Los apellidos solo deben contener letras.',
            'dni.regex'                => 'El documento debe contener entre 8 y 12 dígitos numéricos.',
            'telefono.regex'           => 'El teléfono/celular debe contener entre 7 y 9 dígitos numéricos.',
            'padre_madre.regex'        => 'El nombre del apoderado solo debe contener letras.',
            'recaptcha_token.required' => 'Por favor complete el captcha para continuar.',
            'politica.accepted'        => 'Debe aceptar las políticas de privacidad.',
        ]);

        // Verificación de reCAPTCHA v2 contra la API de Google
        $httpClient = app()->isLocal() ? Http::withoutVerifying() : Http::asForm();
        $secretKey = config('services.recaptcha.secret_key', env('RECAPTCHA_SECRET_KEY'));
        if ($secretKey) {
            $response = $httpClient->asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => $secretKey,
                'response' => $validated['recaptcha_token'],
                'remoteip' => $request->ip(),
            ]);

            if (! $response->json('success')) {
                return back()->withErrors(['recaptcha_token' => 'La validación del captcha falló. Intente nuevamente.']);
            }
        }

        // Generar correlativo anual único según tipo
        $year = now()->year;
        $count = LibroReclamacion::whereYear('created_at', $year)->count() + 1;
        $prefix = $validated['tipo_reclamo'] === 'queja' ? 'QUE' : 'REC';
        $correlativo = sprintf('%s-%d-%04d', $prefix, $year, $count);

        LibroReclamacion::create(array_merge($validated, [
            'correlativo' => $correlativo
        ]));

        // Retorna con el correlativo en sesión para que Vue lo muestre en el modal
        return back()->with([
            'success' => true,
            'correlativo' => $correlativo
        ]);
    }
    public function boletines(): Response
    {
        $boletines = Boletin::query()
            ->where('is_active', true)
            ->orderByDesc('fecha_publicacion')
            ->orderByDesc('id')
            ->paginate(6)
            ->withQueryString();

        return Inertia::render('Web/Boletines', [
            'title' => 'Boletines Semanales - Colegio Andino',
            'boletines' => $boletines,
        ]);
    }
}
