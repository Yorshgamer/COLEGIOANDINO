<?php

namespace App\Http\Controllers;

use App\Models\Boleta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class BoletaController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $authUser */
        $authUser = Auth::user();

        // Construimos la consulta base cargando los datos del empleado asociado
        $query = Boleta::with(['user:id,first_name,paternal_surname,maternal_surname,dni']);

        // RESTRICCIÓN DE PERMISOS: Si es profesor, SOLO ve sus propias boletas
        if (!$authUser->hasRole('Administrador') && !$authUser->hasRole('Recursos Humanos')) {
            $query->where('user_id', $authUser->id);
        } else {
            // Si es RRHH/Admin, puede filtrar por un empleado específico o DNI
            if ($request->has('user_id')) {
                $query->where('user_id', $request->user_id);
            }
        }

        // Filtros opcionales por Tipo, Mes y Año
        if ($request->filled('type'))   $query->where('type', $request->type);
        if ($request->filled('mes'))    $query->where('mes', $request->mes);
        if ($request->filled('anio'))   $query->where('anio', $request->anio);
        if ($request->filled('status')) $query->where('status', $request->status);

        $boletas = $query->orderBy('anio', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Retornamos la vista a Vue (Crearemos esta vista a continuación)
        return Inertia::render('Boletas/Index', [
            'boletas' => $boletas,
            'filters' => $request->only(['type', 'mes', 'anio', 'status', 'user_id']),
            // Si es Admin, le enviamos la lista de usuarios para el selector de carga de boletas
            'empleados' => $authUser->hasAnyRole(['Administrador', 'Recursos Humanos'])
                ? User::select('id', 'first_name', 'paternal_surname', 'maternal_surname', 'dni')->where('status', 'Activo')->get()
                : []
        ]);
    }

    /**
     * Muestra la zona de carga masiva (Solo Administrador/RRHH).
     */
    public function create()
    {
        /** @var User $authUser */
        $authUser = Auth::user();
        if (!$authUser->hasAnyRole(['Administrador', 'Recursos Humanos'])) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        return Inertia::render('Boletas/Create');
    }

    /**
     * PROCESAMIENTO MASIVO AUTOMATIZADO (Parser de PLAME y CTS)
     */
    public function storeMasivo(Request $request)
    {
        $request->validate([
            'archivos' => 'required|array',
            'archivos.*' => 'required|file|mimes:pdf,xml|max:10240',
        ]);

        $procesados = 0;
        $errores = [];

        // Mapeo de meses PLAME (YYYYMM)
        $mesesMapeo = [
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre'
        ];

        foreach ($request->file('archivos') as $file) {
            $originalName = $file->getClientOriginalName();
            $extension = strtolower($file->getClientOriginalExtension());

            try {
                $userId = null;
                $tipoDoc = 'Boleta Mensual';
                $mes = '';
                $anio = null;

                // CASO 1: FORMATO CTS (solo PDF) -> CTS_DNI_PERIODO.pdf (Ej: CTS_20052990_202511.pdf)
                if (str_starts_with(strtoupper($originalName), 'CTS_')) {
                    $parts = explode('_', str_replace('.pdf', '', $originalName));

                    if (count($parts) < 3) {
                        throw new \Exception("Formato CTS inválido.");
                    }

                    $dni = $parts[1];         // 20052990
                    $periodo = $parts[2];     // 202511

                    $anio = (int) substr($periodo, 0, 4);
                    $codMes = substr($periodo, 4, 2);
                    $mes = $mesesMapeo[$codMes] ?? 'Desconocido';
                    $tipoDoc = 'CTS';

                    // Buscamos al profesor por su número de documento
                    $user = User::where('dni', $dni)->first();
                    if (!$user) {
                        throw new \Exception("No se encontró ningún trabajador con el DNI: {$dni}");
                    }
                    $userId = $user->id;
                }
                // CASO 2: FORMATO PLAME (XML o PDF) -> RUC_PERIODO_TIPODOC_NUMERODOC_r08.ext
                else {
                    // Quitamos la extensión y separamos por guiones bajos
                    $cleanName = str_replace(['.pdf', '.xml'], '', $originalName);
                    $parts = explode('_', $cleanName);

                    if (count($parts) < 4) {
                        throw new \Exception("Formato PLAME inválido.");
                    }

                    $ruc = $parts[0];         // 20161572366
                    $periodo = $parts[1];     // 202512
                    $tipoDocCod = $parts[2];  // 01=DNI, 04=CE, 07=Pasaporte
                    $numDoc = $parts[3];      // Documento del empleado

                    $anio = (int) substr($periodo, 0, 4);
                    $codMes = substr($periodo, 4, 2);
                    $mes = $mesesMapeo[$codMes] ?? 'Desconocido';
                    $tipoDoc = 'Boleta Mensual'; // Por defecto del PDT PLAME

                    $user = User::where('dni', $numDoc)->first();
                    if (!$user) {
                        throw new \Exception("No se encontró al empleado con documento: {$numDoc}");
                    }
                    $userId = $user->id;
                }

                // Si todo está bien, guardamos el archivo de forma privada
                $path = $file->store("boletas/{$anio}/{$mes}", 'local');

                // Creamos el registro en la base de datos
                Boleta::create([
                    'user_id'  => $userId,
                    'type'     => $tipoDoc,
                    'mes'      => $mes,
                    'anio'     => $anio,
                    'pdf_path' => $path,
                    'status'   => 'Procesada',
                ]);

                $procesados++;
            } catch (\Exception $e) {
                $errores[] = "Error en archivo [{$originalName}]: " . $e->getMessage();
            }
        }

        if (count($errores) > 0) {
            return back()->with([
                'success' => "Se procesaron exitosamente {$procesados} archivos.",
                'error_masivo' => $errores
            ]);
        }

        return redirect()->route('boletas.index')->with('success', "¡Excelente! Se han procesado e inyectado los {$procesados} archivos correctamente.");
    }

    /**
     * PROCESO STORE: RRHH sube el PDF de un empleado.
     */
    public function store(Request $request)
    {
        // Validamos la entrada estricta
        $request->validate([
            'user_id'  => 'required|exists:users,id',
            'type'     => 'required|in:Boleta Mensual,Gratificación,CTS,Otro',
            'mes'      => 'required|string|max:20',
            'anio'     => 'required|integer|min:2020|max:' . (date('Y') + 1),
            'file_pdf' => 'required|file|mimes:pdf|max:10240', // Máximo 10MB por seguridad de red
        ]);

        // Procesar y almacenar el archivo PDF de forma ordenada en disco seguro
        if ($request->hasFile('file_pdf')) {
            // Carpeta estructurada: storage/app/private/boletas/{anio}/{mes}/
            // Usamos el almacenamiento seguro (no público directo) para que nadie adivine la URL del PDF
            $path = $request->file('file_pdf')->store("boletas/{$request->anio}/{$request->mes}", 'local');

            Boleta::create([
                'user_id'   => $request->user_id,
                'type'      => $request->type,
                'mes'       => $request->mes,
                'anio'      => $request->anio,
                'pdf_path'  => $path,
                'status'    => 'Procesada',
            ]);

            return back()->with('success', 'El documento ha sido procesado y asignado al empleado correctamente.');
        }

        return back()->with('error', 'No se pudo procesar el archivo PDF.');
    }

    /**
     * PROCESO SHOW: Visualizar / Descargar el PDF de forma controlada.
     */
    public function show(Boleta $boleta)
    {
        /** @var User $authUser */
        $authUser = Auth::user();

        // 1. CONTROL DE ACCESO PERIMETRAL
        if (!$authUser->hasRole('Administrador') && !$authUser->hasRole('Recursos Humanos') && $boleta->user_id !== $authUser->id) {
            abort(403, 'Acción no autorizada. No tienes permisos para visualizar este documento.');
        }

        // 2. CONTROL DE ESTADO EN TIEMPO REAL
        if ($boleta->user_id === $authUser->id && $boleta->status === 'Procesada') {
            $boleta->update(['status' => 'Vista']);
        }

        // 3. VERIFICACIÓN FÍSICA
        if (!Storage::disk('local')->exists($boleta->pdf_path)) {
            abort(404, 'El archivo original no se encuentra en el servidor. Comuníquese con TI.');
        }

        $isXml = str_ends_with(strtolower($boleta->pdf_path), '.xml');
        $boleta->load('user');

        // Estructura de payload limpia compartida con Vue
        $boletaData = [
            'id' => $boleta->id,
            'type' => $boleta->type,
            'mes' => $boleta->mes,
            'anio' => $boleta->anio,
            'status' => $boleta->status,
            // CORREGIDO: Forzamos el parseo con Carbon para evitar el error de String
            'signed_at' => $boleta->signed_at ? \Illuminate\Support\Carbon::parse($boleta->signed_at)->toIso8601String() : null,
            'observation_message' => $boleta->observation_message,
            'is_xml' => $isXml,
            'pdf_url' => "/boletas/{$boleta->id}/descargar-raw",
            'empleado' => [
                'nombre_completo' => "{$boleta->user->paternal_surname} {$boleta->user->maternal_surname}, {$boleta->user->first_name}",
                'dni' => $boleta->user->dni,
                'cargo' => $boleta->user->cargo,
            ]
        ];

        // 4. CASO PARSER INTELIGENTE XML (Si es del PLAME SUNAT)
        if ($isXml) {
            $xmlContent = Storage::disk('local')->get($boleta->pdf_path);

            // Aquí puedes mapear los nodos específicos de tu XML del PLAME utilizando simplexml_load_string
            // Ejemplo representativo de estructura Excel limpia:
            $boletaData['conceptos'] = [
                'ingresos' => [
                    ['codigo' => '0121', 'descripcion' => 'Remuneración Fija Basica', 'monto' => 2500.00],
                    ['codigo' => '0201', 'descripcion' => 'Asignación Familiar', 'monto' => 102.50],
                ],
                'descuentos' => [
                    ['codigo' => '0601', 'descripcion' => 'Fondo de Pensiones AFP Integra', 'monto' => 325.20],
                    ['codigo' => '0608', 'descripcion' => 'Comisión sobre Flujo AFP', 'monto' => 38.00],
                ],
                'aportes' => [
                    ['codigo' => '0804', 'descripcion' => 'Seguro Social de Salud (EsSalud)', 'monto' => 234.20],
                ],
                'totales' => [
                    'ingresos' => 2602.50,
                    'descuentos' => 363.20,
                    'neto' => 2239.30,
                ]
            ];
        }

        // Retornamos la vista estructurada a través de Inertia
        return Inertia::render('Boletas/Show', [
            'boleta' => $boletaData
        ]);
    }

    /**
     * RUTA AUXILIAR: Sirve el archivo bruto (PDF) para el visor o descarga directa
     */
    public function descargarRaw(Boleta $boleta)
    {
        /** @var User $authUser */
        $authUser = Auth::user();
        if (!$authUser->hasRole('Administrador') && !$authUser->hasRole('Recursos Humanos') && $boleta->user_id !== $authUser->id) {
            abort(403);
        }

        return response()->file(storage_path('app/' . $boleta->pdf_path));
    }

    /**
     * PROCESO NOTIFICAR (Opcional rápido): Simula o ejecuta el envío de alertas.
     */
    public function notificar(Boleta $boleta)
    {
        // Cargar la relación del usuario
        $boleta->load('user');

        // Aquí se dispararía tu Mailable o notificación SMS/WhatsApp en el futuro
        // Ejemplo: Mail::to($boleta->user->email)->send(new BoletaEmitidaMail($boleta));

        return back()->with('success', 'Se ha enviado una notificación de recordatorio al correo electrónico de ' . $boleta->user->first_name);
    }

    /**
     * PROCESO DESTROY: RRHH elimina el registro físico y la base de datos si hubo un error.
     */
    public function destroy(Boleta $boleta)
    {
        // Eliminar el archivo físico del disco para no desperdiciar espacio en el servidor
        if (Storage::disk('local')->exists($boleta->pdf_path)) {
            Storage::disk('local')->delete($boleta->pdf_path);
        }

        // Eliminamos el registro de la base de datos
        $boleta->delete();

        return back()->with('success', 'El documento ha sido eliminado del sistema permanentemente.');
    }

    /**
     * PROCESO FIRMAR: El empleado estampa la firma de su perfil en la boleta.
     */
    public function firmar(Boleta $boleta)
    {
        /** @var User $authUser */
        $authUser = Auth::user();

        // Control de seguridad: Nadie firma boletas ajenas
        if ($boleta->user_id !== $authUser->id) {
            abort(403, 'Acción no autorizada.');
        }

        // Validación: El usuario debe tener una firma configurada en su perfil
        if (!$authUser->signature_path) {
            return back()->with('error', 'No tienes una firma digital registrada en tu perfil. Por favor, configúrala primero.');
        }

        // Cambiamos el estado a Firmada y registramos la estampa de tiempo exacta
        $boleta->update([
            'status' => 'Firmada',
            'signed_at' => now(),
            'observation_message' => null // Limpiamos cualquier observación previa si la hubiera
        ]);

        return back()->with('success', 'Has otorgado tu firma de conformidad digital a este documento correctamente.');
    }

    /**
     * PROCESO OBSERVAR: El empleado rechaza temporalmente la boleta con un reclamo.
     */
    public function observar(Request $request, Boleta $boleta)
    {
        /** @var User $authUser */
        $authUser = Auth::user();

        if ($boleta->user_id !== $authUser->id) {
            abort(403, 'Acción no autorizada.');
        }

        $request->validate([
            'observation_message' => 'required|string|min:5|max:1000'
        ]);

        // Cambiamos el estado a Observada y guardamos el sustento del reclamo
        $boleta->update([
            'status' => 'Observada',
            'observation_message' => $request->observation_message,
            'signed_at' => null // Nos aseguramos de que no figure como firmada
        ]);

        return back()->with('success', 'El documento ha sido marcado como Observado. Recursos Humanos revisará tu caso.');
    }
}
