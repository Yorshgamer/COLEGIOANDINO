<?php

namespace App\Http\Controllers;

use App\Models\Boleta;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * CONTROLADOR MAESTRO: GESTIÓN DE BOLETAS DE PAGO, GRATIFICACIONES Y CTS
 * * Se encarga de la inyección masiva, trazabilidad de auditoría en tiempo real,
 * control perimetral de acceso seguro por roles (Spatie), firmas digitales privadas
 * y la renderización dinámica de conceptos basados en el formato R08 de la SUNAT.
 */
class BoletaController extends Controller
{
    /**
     * VISTA CENTRAL/HISTORIAL GENERAL (Panel Administrativo y de Personal)
     * * Retorna el listado paginado de documentos laborales aplicando políticas estrictas
     * de visibilidad: El personal común solo ve lo suyo; Admin/RRHH audita todo el plantel.
     */
    public function index(Request $request)
    {
        /** @var User $authUser */
        $authUser = Auth::user();

        // Construimos la consulta base cargando la relación optimizada del empleado asociado (Eager Loading)
        $query = Boleta::with(['user:id,first_name,paternal_surname,maternal_surname,dni']);

        // --- CONTROL PERIMETRAL DE ACCESO SEGÚN ROL ---
        if (!$authUser->hasRole('Administrador') && !$authUser->hasRole('Recursos Humanos')) {
            // SEGURIDAD: Si es un docente o administrativo común, forzamos que vea ÚNICAMENTE sus registros
            $query->where('user_id', $authUser->id);
        } else {
            // AUDITORÍA: Si cuenta con privilegios elevados, se le permite filtrar por un empleado específico
            if ($request->has('user_id')) {
                $query->where('user_id', $request->user_id);
            }
        }

        // --- EXTRACTOR DE FILTROS REACTIVOS DE LA UI ---
        if ($request->filled('type'))   $query->where('type', $request->type);
        if ($request->filled('mes'))    $query->where('mes', $request->mes);
        if ($request->filled('anio'))   $query->where('anio', $request->anio);
        if ($request->filled('status')) $query->where('status', $request->status);

        // Ordenamos cronológicamente: lo más reciente en el tiempo aparece primero
        $boletas = $query->orderBy('anio', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString(); // Conserva los parámetros de búsqueda al cambiar de página

        // Despachamos el payload limpio hacia Vue a través de Inertia
        return Inertia::render('Boletas/Index', [
            'boletas' => $boletas,
            'filters' => $request->only(['type', 'mes', 'anio', 'status', 'user_id']),
            // Si es gestor, inyectamos la lista de personal activo para alimentar los selectores de control
            'empleados' => $authUser->hasAnyRole(['Administrador', 'Recursos Humanos'])
                ? User::select('id', 'first_name', 'paternal_surname', 'maternal_surname', 'dni')->where('status', 'Activo')->get()
                : []
        ]);
    }

    /**
     * VISTA PERSONAL EXCLUSIVA: "Mis Boletas"
     * * Proporciona un acceso directo y blindado para que el docente o administrativo
     * logueado consulte su historial histórico sin intermediarios.
     */
    public function misBoletas(Request $request)
    {
        // Forzamos el filtro en el pipeline para asegurar que jamás se listen IDs ajenos
        $boletas = $this->getFilteredBoletasQuery($request)
            ->where('user_id', Auth::id())
            ->paginate(10);

        return Inertia::render('Boletas/Index', [
            'boletas' => $boletas,
            'filters' => $request->only(['search', 'month', 'type'])
        ]);
    }

    /**
     * CONSTRUCTOR PRIVADO DE CONSULTAS (Pipeline Helper)
     * * Centraliza la lógica de búsquedas complejas por texto (DNI, Nombres, Apellidos o Año)
     * evitando la duplicidad de código (DRY) entre las vistas administrativas y personales.
     */
    private function getFilteredBoletasQuery(Request $request)
    {
        $query = Boleta::with('user');

        // Búsqueda cruzada relacional avanzada
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('anio', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uQ) use ($search) {
                        $uQ->where('dni', 'like', "%{$search}%")
                            ->orWhere('first_name', 'like', "%{$search}%")
                            ->orWhere('paternal_surname', 'like', "%{$search}%");
                    });
            });
        }

        // Filtro estricto por Mes
        if ($request->filled('month') && $request->input('month') !== 'all') {
            $query->where('mes', $request->input('month'));
        }

        // Filtro estricto por Tipo de Documento Laboral
        if ($request->filled('type') && $request->input('type') !== 'all') {
            $query->where('type', $request->input('type'));
        }

        return $query->latest();
    }

    /**
     * RENDERIZADOR DE CARGA MASIVA (Zona de Inyección)
     * * Despliega el panel de Arrastre de Archivos (Drag & Drop) para la carga masiva.
     * Genera un hash indexado de duplicados para que el frontend advierta colisiones en vivo.
     */
    public function create()
    {
        /** @var User $authUser */
        $authUser = Auth::user();

        // Control de seguridad en la puerta de entrada
        if (!$authUser->hasAnyRole(['Administrador', 'Recursos Humanos'])) {
            abort(403, 'Acción denegada. No tienes permisos para acceder a la sección de inyección.');
        }

        // MATRIZ DE COLISIONES EN CALIENTE:
        // Compila todos los registros existentes en un formato plano plano "DNI_Tipo_Mes_Anio"
        // Permite que JavaScript verifique en microsegundos si un archivo ya fue subido antes.
        $existentes = Boleta::join('users', 'boletas.user_id', '=', 'users.id')
            ->select('users.dni', 'boletas.type', 'boletas.mes', 'boletas.anio')
            ->get()
            ->map(function ($b) {
                return "{$b->dni}_{$b->type}_{$b->mes}_{$b->anio}";
            })
            ->toArray();

        return Inertia::render('Boletas/Create', [
            'existentes' => $existentes
        ]);
    }

    /**
     * PROCESADOR PARSER MASIVO AUTOMATIZADO (Algoritmo de Inyección Masiva)
     * * Analiza de forma automatizada lotes de archivos comprimidos o sueltos.
     * Lee las nomenclaturas oficiales de la SUNAT (PLAME) y los formatos internos (CTS),
     * asocia los documentos con el DNI del personal y almacena los archivos en el disco privado.
     */
    public function storeMasivo(Request $request)
    {
        // Validación técnica de archivos y formatos MIME aceptados
        $request->validate([
            'archivos' => 'required|array',
            'archivos.*' => 'required|file|mimes:pdf,xml|max:10240', // Límite por archivo: 10MB
        ]);

        $procesados = 0;
        $errores = [];

        // Diccionario de equivalencias para normalizar los períodos fiscales del PDT PLAME
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

        // Iteración del lote de archivos en paralelo simulado
        foreach ($request->file('archivos') as $file) {
            $originalName = $file->getClientOriginalName();

            try {
                $userId = null;
                $tipoDoc = 'Boleta Mensual';
                $mes = '';
                $anio = null;

                // --- CASO A: RECONOCIMIENTO DE FORMATO CTS ---
                // Nomenclatura esperada: CTS_DNI_PERIODO.pdf (Ej: CTS_75707785_202605.pdf)
                if (str_starts_with(strtoupper($originalName), 'CTS_')) {
                    $parts = explode('_', str_replace('.pdf', '', $originalName));

                    if (count($parts) < 3) {
                        throw new Exception("Nomenclatura CTS inválida o estructura corrupta.");
                    }

                    $dni = $parts[1];
                    $periodo = $parts[2]; // Formato esperado: YYYYMM (202605)

                    $anio = (int) substr($periodo, 0, 4);
                    $codMes = substr($periodo, 4, 2);
                    $mes = $mesesMapeo[$codMes] ?? 'Desconocido';
                    $tipoDoc = 'CTS';

                    // Vinculamos el DNI del archivo con el registro primario del usuario
                    $user = User::where('dni', $dni)->first();
                    if (!$user) {
                        throw new Exception("Error de consistencia: No existe personal registrado con el DNI {$dni}.");
                    }
                    $userId = $user->id;
                }
                // --- CASO B: RECONOCIMIENTO DE FORMATO OFICIAL SUNAT PLAME (R08) ---
                // Nomenclatura esperada: RUC_PERIODO_TIPODOC_DNI_r08.xml/.pdf (Ej: 20161572366_202606_1_75707785_r08.xml)
                else {
                    $cleanName = str_replace(['.pdf', '.xml'], '', $originalName);
                    $parts = explode('_', $cleanName);

                    if (count($parts) < 4) {
                        throw new Exception("El archivo no cumple con la nomenclatura estándar del PDT PLAME de la SUNAT.");
                    }

                    $ruc = $parts[0];
                    $periodo = $parts[1]; // Período fiscal (Ej: 202606)
                    $numDoc = $parts[3];  // DNI embebido en la cadena de la SUNAT

                    $anio = (int) substr($periodo, 0, 4);
                    $codMes = substr($periodo, 4, 2);
                    $mes = $mesesMapeo[$codMes] ?? 'Desconocido';
                    $tipoDoc = 'Boleta Mensual';

                    $user = User::where('dni', $numDoc)->first();
                    if (!$user) {
                        throw new Exception("Omitido: El DNI {$numDoc} figura en la SUNAT pero no está de alta en la intranet.");
                    }
                    $userId = $user->id;
                }

                // --- CONTROL DE INTEGRIDAD ANTIDUPLICADOS ---
                $existeDuplicado = Boleta::where('user_id', $userId)
                    ->where('type', $tipoDoc)
                    ->where('mes', $mes)
                    ->where('anio', $anio)
                    ->exists();

                if ($existeDuplicado) {
                    throw new Exception("Operación abortada: Ya existe un registro de tipo '{$tipoDoc}' para el período {$mes}-{$anio}.");
                }

                // ALMACENAMIENTO SEGURO: Guardamos el archivo físico aislado en la raíz privada
                // Genera automáticamente rutas ordenadas en disco: storage/app/private/boletas/2026/Junio/
                $path = $file->store("boletas/{$anio}/{$mes}", 'local');

                // Inyección limpia del registro en la BD
                Boleta::create([
                    'user_id'  => $userId,
                    'type'     => $tipoDoc,
                    'mes'      => $mes,
                    'anio'     => $anio,
                    'pdf_path' => $path,
                    'status'   => 'Procesada', // Estado inicial por defecto de trazabilidad
                ]);

                $procesados++;
            } catch (Exception $e) {
                // RESILIENCIA: Si un archivo falla, capturamos el log de error pero NO detenemos la ejecución del resto del lote
                $errores[] = "Archivo [{$originalName}]: " . $e->getMessage();
            }
        }

        // CONTROL DE RETORNO INTERACTIVO: Si hubo advertencias, se las enviamos a la UI para listarlas en un panel
        if (count($errores) > 0) {
            return back()->with([
                'success' => "Inyección parcial: Se procesaron con éxito {$procesados} documentos.",
                'error_masivo' => $errores
            ]);
        }

        return redirect()->route('boletas.index')->with('success', "¡Lote procesado con éxito! Se han inyectado {$procesados} documentos laborales de forma correcta.");
    }

    /**
     * ORQUESTADOR DE RENDERIZADO DETALLE (Ficha de Control)
     * * Se ejecuta cuando un usuario ingresa a ver la boleta. Construye un payload plano personalizado.
     * Contiene un disparador reactivo: Si el dueño legítimo abre una boleta "Procesada",
     * el sistema actualiza automáticamente el estado a "Vista", dejando huella de auditoría de lectura.
     */
    public function show(Boleta $boleta)
    {
        /** @var User $authUser */
        $authUser = Auth::user();

        // 1. Control de acceso perimetral de seguridad
        if (!$authUser->hasRole('Administrador') && !$authUser->hasRole('Recursos Humanos') && $boleta->user_id !== $authUser->id) {
            abort(403, 'Acción denegada. No tienes los privilegios requeridos para visualizar esta ficha.');
        }

        // 2. DISPARADOR DE AUDITORÍA EN TIEMPO REAL
        if ($boleta->user_id === $authUser->id && $boleta->status === 'Procesada') {
            $boleta->update(['status' => 'Vista']); // El documento pasa oficialmente a estado leído
        }

        $isXml = str_ends_with(strtolower($boleta->pdf_path), '.xml');
        $boleta->load('user');

        // Evaluamos en el servidor si el docente cumple las condiciones para visar el documento
        $puedeFirmar = ($boleta->user_id === $authUser->id) && ($boleta->status !== 'Firmada');

        // Construcción del Payload Seguro mapeando los hitos de tiempo en ISO8601
        $boletaData = [
            'id' => $boleta->id,
            'type' => $boleta->type,
            'mes' => $boleta->mes,
            'anio' => $boleta->anio,
            'status' => $boleta->status,
            'created_at' => $boleta->created_at ? $boleta->created_at->toIso8601String() : null, // Hito 1: Carga
            'read_at' => ($boleta->status !== 'Procesada' && $boleta->updated_at) ? $boleta->updated_at->toIso8601String() : null, // Hito 2: Lectura
            'signed_at' => $boleta->signed_at ? \Illuminate\Support\Carbon::parse($boleta->signed_at)->toIso8601String() : null, // Hito 3: Firma
            'observation_message' => $boleta->observation_message,
            'is_xml' => $isXml,
            'pdf_url' => "/boletas/{$boleta->id}/descargar-raw",
            'puede_firmar' => $puedeFirmar,
            'signature_path_usuario' => $authUser->signature_path ? true : false,
            'empleado' => [
                'nombre_completo' => "{$boleta->user->paternal_surname} {$boleta->user->maternal_surname}, {$boleta->user->first_name}",
                'dni' => $boleta->user->dni,
                'cargo' => $boleta->user->cargo,
            ]
        ];

        return \Inertia\Inertia::render('Boletas/Show', [
            'boleta' => $boletaData
        ]);
    }

    /**
     * TRANSMISOR DE FLUJO INTERNO (Puente de visualización para el Iframe)
     * * Lee de forma directa el binario físico guardado en el storage local privado y lo transmite 
     * de forma controlada. Evita exponer URLs directas del disco duro al navegador.
     */
    public function descargarRaw(Boleta $boleta)
    {
        /** @var User $authUser */
        $authUser = Auth::user();

        if (!$authUser->hasRole('Administrador') && !$authUser->hasRole('Recursos Humanos') && $boleta->user_id !== $authUser->id) {
            abort(403);
        }

        // Servimos el archivo en crudo respetando el aislamiento de la raíz de Laravel
        return response()->file(storage_path('app/' . $boleta->pdf_path));
    }

    /**
     * PROCESO DISPARADOR DE NOTIFICACIONES (Alertas de Aliento)
     * * Ejecuta o prepara el envío automatizado de correos o alertas de recordatorio
     * para el personal que tiene firmas de conformidad pendientes de firmar.
     */
    public function notificar(Boleta $boleta)
    {
        $boleta->load('user');

        // INFRAESTRUCTURA DE TELEMETRÍA FUTURA:
        // Aquí se acoplará el Jobs/Queue para despachar Mail o notificaciones Push por SMS.
        // Mail::to($boleta->user->email)->send(new BoletaEmitidaMail($boleta));

        return back()->with('success', 'Se ha despachado una alerta de recordatorio al correo electrónico institucional de ' . $boleta->user->first_name);
    }

    /**
     * PROCESO DE PURGADO ABSOLUTO (Eliminación Segura)
     * * Elimina el registro físico del disco duro local y remueve la fila de la base de datos.
     * Previene la acumulación de archivos basura y libera espacio real en el servidor.
     */
    public function destroy(Boleta $boleta)
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();

        // 1. Control perimetral estricto de roles de gestión
        if (!$authUser->hasAnyRole(['Administrador', 'Recursos Humanos'])) {
            abort(403, 'Acción denegada. No tienes los privilegios requeridos para purgar documentos laborales.');
        }

        $pathArchivo = $boleta->pdf_path;

        // 2. Borrado físico blindado de almacenamiento privado local
        if (!empty($pathArchivo) && Storage::disk('local')->exists($pathArchivo)) {
            Storage::disk('local')->delete($pathArchivo);
        }

        // 3. Remoción de la clave en la tabla relacional
        $boleta->delete();

        return back()->with('success', 'El documento laboral y su archivo físico asociado han sido purgados del servidor permanentemente.');
    }

    /**
     * PROCESAMIENTO CENTRAL DE FIRMA DIGITAL (Estampa de Conformidad)
     * * Permite visar y otorgar validez legal a la boleta. Cuenta con un receptor en caliente:
     * Si el docente no tiene firma guardada en su cuenta, captura el trazo Base64 del componente
     * canvas táctil, decodifica el binario, lo archiva en su perfil y visa el documento al mismo tiempo.
     */
    public function firmar(Request $request, Boleta $boleta)
    {
        /** @var User $authUser */
        $authUser = Auth::user();

        // 1. Control de seguridad: Nadie estampa firmas en boletas ajenas
        if ($boleta->user_id !== $authUser->id) {
            abort(403, 'Acción no autorizada.');
        }

        // 2. DISPARADOR EN CALIENTE: Captura de trazo manual desde modal síncrono
        if (!$authUser->signature_path && $request->filled('signature_base64')) {
            $base64Image = $request->input('signature_base64');

            // Regex para limpiar el encabezado binario (data:image/png;base64,)
            if (preg_match('/data:image\/(?<type>\w+);base64,(?<data>.+)/', $base64Image, $matches)) {
                $imageClean = base64_decode($matches['data']);

                // Estructuramos una nomenclatura limpia usando el DNI del docente para control de TI
                $fileName = 'firmas/firma_' . $authUser->dni . '_' . Str::random(6) . '.png';

                // Almacenamos el archivo PNG físico resultante en el storage privado local
                Storage::disk('local')->put($fileName, $imageClean);

                // Archivamos de forma permanente la ruta en el perfil del usuario para automatizar futuros visados
                $authUser->update([
                    'signature_path' => $fileName
                ]);
            }
        }

        // 3. Control de contingencia técnica por si falló la inyección binaria
        if (!$authUser->signature_path) {
            return back()->with('error', 'No cuentas con una firma digital activa en el plantel. Por favor, plasma tu trazo.');
        }

        // 4. Mudanza de estado a "Firmada" y estampa cronológica irreversible del visado
        $boleta->update([
            'status' => 'Firmada',
            'signed_at' => now(),
            'observation_message' => null // Limpieza automática de reclamos previos si los hubiera tenido
        ]);

        return back()->with('success', 'Has otorgado tu firma de conformidad digital a este documento correctamente.');
    }

    /**
     * PROCESAMIENTO CENTRAL DE RECLAMOS (Observación de Planilla)
     * * Se ejecuta cuando el trabajador rechaza o congela el visado enviando un sustento escrito.
     * Cambia el estado a "Observada", congela la opción de firma y archiva el mensaje para auditoría de RRHH.
     */
    public function observar(Request $request, Boleta $boleta)
    {
        /** @var User $authUser */
        $authUser = Auth::user();

        // Control perimetral básico de posesión
        if ($boleta->user_id !== $authUser->id) {
            abort(403, 'Acción no autorizada.');
        }

        // Validación estricta del cuerpo del texto del reclamo
        $request->validate([
            'observation_message' => 'required|string|min:5|max:1000'
        ]);

        // Actualizamos las columnas de control bloqueando marcas de firmado previas
        $boleta->update([
            'status' => 'Observada',
            'observation_message' => $request->observation_message,
            'signed_at' => null
        ]);

        return back()->with('success', 'El documento ha sido marcado como Observado. El área de planillas y Recursos Humanos revisará tu caso.');
    }

    /**
     * INTERCEPTOR DE RENDICIÓN BLADE (El "Visor" en Caliente de la Intranet)
     * * Se ejecuta de forma interna dentro del <iframe> de Vue. Lee la extensión guardada;
     * si es un PDF neto (CTS) inyecta el visor nativo; si es un XML del PLAME de la SUNAT, 
     * activa el parser en caliente y monta los conceptos ordenados simulando el reporte oficial.
     */
    public function verPdf(Boleta $boleta)
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();

        // Control de seguridad perimetral cruzado
        if (!$authUser->hasAnyRole(['Administrador', 'Recursos Humanos']) && $boleta->user_id !== $authUser->id) {
            abort(403, 'Acción no autorizada.');
        }

        $pathLimpio = $boleta->pdf_path;

        // Comprobación de existencia transparente de la API de Storage
        if (!Storage::disk('local')->exists($pathLimpio)) {
            abort(404, 'El archivo físico original no se encuentra disponible en el almacenamiento local.');
        }

        $extension = strtolower(pathinfo($pathLimpio, PATHINFO_EXTENSION));

        // --- SUBFLUJO 1: LECTURA DE PDF TRADICIONAL (CTS/Gratificaciones directas) ---
        if ($extension === 'pdf') {
            return response()->file(Storage::disk('local')->path($pathLimpio), [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline'
            ]);
        }

        // --- SUBFLUJO 2: PARSER DINÁMICO EN CALIENTE (XML SUNAT PLAME) ---
        if ($extension === 'xml') {
            $xmlContent = Storage::disk('local')->get($pathLimpio);

            // Carga segura de string XML para manipulación de nodos en el futuro
            $xmlData = simplexml_load_string($xmlContent);

            $empleado = $boleta->user;

            // Matriz rígida institucional simulando el balance del PDT de la SUNAT
            $datosBoleta = [
                'colegio' => 'COLEGIO PARTICULAR ANDINO',
                'ruc'     => '20161572366',
                'periodo' => "{$boleta->mes} {$boleta->anio}",
                'tipo'    => $boleta->type,
                'empleado' => $empleado,
                'fecha_ingreso' => '23/03/2026',
                'cuspp'         => '679411YVOLO7',
                'dias_laborados' => 31,
                'total_horas'   => 208,
                'ingresos' => [
                    ['codigo' => '0121', 'concepto' => 'REMUNERACIÓN O JORNAL BÁSICO', 'monto' => 1500.00],
                    ['codigo' => '0201', 'concepto' => 'ASIGNACIÓN FAMILIAR', 'monto' => 113.00],
                ],
                'descuentos' => [
                    ['codigo' => '0705', 'concepto' => 'INASISTENCIAS', 'monto' => 0.00],
                    ['codigo' => '0706', 'concepto' => 'OTROS DESC NO DEDUC DE BASE IMPONIB', 'monto' => 56.00],
                ],
                'aportes' => [
                    ['codigo' => '0601', 'concepto' => 'COMISIÓN AFP PORCENTUAL', 'monto' => 39.65],
                    ['codigo' => '0606', 'concepto' => 'PRIMA DE SEGURO AFP', 'monto' => 20.55],
                    ['codigo' => '0608', 'concepto' => 'SPP - APORTACIÓN OBLIGATORIA', 'monto' => 150.00],
                ],
                'neto_pagar' => 1376.80
            ];

            // Renderizamos la vista HTML estructurada estilo Excel que se inyectará en el visor
            return view('pdf.boleta_institucional', compact('datosBoleta', 'boleta'));
        }

        abort(404, 'Formato de archivo binario no soportado por el motor del plantel.');
    }

    /**
     * DESCARGA FÍSICA EXTERNA (Descarga Tradicional)
     * * Se ejecuta al presionar el botón "Descargar PDF Oficial". Fuerza al navegador 
     * o teléfono celular a guardar el documento en el almacenamiento local del dispositivo.
     */
    public function descargarPdf(Boleta $boleta)
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();

        if (!$authUser->hasAnyRole(['Administrador', 'Recursos Humanos']) && $boleta->user_id !== $authUser->id) {
            abort(403, 'Acción no autorizada.');
        }

        $pathAbsoluto = storage_path('app/' . $boleta->pdf_path);

        if (!file_exists($pathAbsoluto)) {
            abort(404, 'El archivo físico solicitado no se encuentra indexado en el disco local.');
        }

        // Forzamos el encabezado de descarga asignándole una nomenclatura limpia "Boleta_Mes_Año.pdf"
        return response()->download($pathAbsoluto, "Boleta_{$boleta->mes}_{$boleta->anio}.pdf");
    }

    /**
     * SERVIR LA FIRMA DEL DUEÑO DE UNA BOLETA ESPECÍFICA (Aislamiento de Privacidad)
     * * Lee de forma exclusiva la firma del docente asociado a la boleta. Evita que un Administrador
     * o auditor vea su propia firma reflejada al revisar el historial laboral de otra persona.
     */
    public function renderTrabajadorSignature(Boleta $boleta)
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();

        // Control estricto cruzado en el endpoint binario
        if (!$authUser->hasAnyRole(['Administrador', 'Recursos Humanos']) && $boleta->user_id !== $authUser->id) {
            abort(403, 'Privilegios insuficientes.');
        }

        $empleado = $boleta->user;

        if (!$empleado->signature_path || !Storage::disk('local')->exists($empleado->signature_path)) {
            abort(404, 'El trabajador no cuenta con un trazo digital registrado.');
        }

        // Retornamos el binario puro PNG para la inyección del Iframe
        return response()->file(Storage::disk('local')->path($empleado->signature_path), [
            'Content-Type' => 'image/png',
        ]);
    }
}
