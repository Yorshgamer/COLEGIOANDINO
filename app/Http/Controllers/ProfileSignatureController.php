<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * CONTROLADOR DE AUDITORÍA: GESTIÓN DE SEGURIDAD Y RENDERIZADO DE FIRMAS PRIVADAS
 * * Centraliza el registro de firmas de perfil de usuario y la entrega segura de
 * imágenes binarias hacia las vistas de Vue e Iframes, impidiendo la exposición pública.
 */
class ProfileSignatureController extends Controller
{
    /**
     * ACTUALIZAR / REGISTRAR FIRMA DEL TRABAJADOR
     * * Recibe el trazo del lienzo digital en formato Base64, realiza la limpieza
     * del string, purga firmas obsoletas para evitar basura y almacena el archivo en el disco local privado.
     */
    public function update(Request $request)
    {
        // Validación de entrada: El payload de la firma es estrictamente requerido
        $request->validate([
            'signature' => 'required|string'
        ]);

        /** @var User $user */
        $user = Auth::user();

        // --- HITO 1: PURGADO DE SEGURIDAD INTERNO ---
        // Si el usuario ya contaba con una firma previa, la eliminamos físicamente del disco privado
        if ($user->signature_path && Storage::disk('local')->exists($user->signature_path)) {
            Storage::disk('local')->delete($user->signature_path);
        }

        // --- HITO 2: DECODIFICACIÓN Y LIMPIEZA BINARIA ---
        // Removemos el encabezado del Data URL de la firma emitido por el Canvas táctil
        $imageData = $request->signature;
        $image = str_replace('data:image/png;base64,', '', $imageData);
        $image = str_replace(' ', '+', $image); // Corrección de espacios por pérdidas en la transmisión HTTP

        // --- HITO 3: NOMENCLATURA PROFESIONAL DE ARCHIVOS ---
        // Generamos un slug seguro (ej. "juan-perez") para evitar caracteres especiales en Linux/Unix
        $employeeSlug = Str::slug($user->first_name . '_' . $user->paternal_surname);
        $imageName = 'firmas/firma_' . $employeeSlug . '_' . $user->dni . '.png';

        // --- HITO 4: ALMACENAMIENTO PERIMETRAL EN DISCO PRIVADO ---
        // Guardamos el flujo binario decodificado en la raíz protegida 'storage/app/private/'
        Storage::disk('local')->put($imageName, base64_decode($image));

        // --- HITO 5: ACTUALIZACIÓN DE REGISTRO EN BASE DE DATOS ---
        $user->update([
            'signature_path' => $imageName
        ]);

        return back()->with('success', 'Firma institucional registrada en almacenamiento seguro.');
    }

    /**
     * PUENTE DE RED SEGURO: Renderizar Imagen del Usuario Conectado
     * * Intercepta las solicitudes del navegador para la vista previa del perfil, lee el binario
     * privado y lo sirve como flujo HTTP con encabezados de caché privados optimizados.
     */
    public function renderImage()
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();

        // Control de contingencia: Si no tiene firma asignada o el archivo fue borrado del disco
        if (!$authUser->signature_path || !Storage::disk('local')->exists($authUser->signature_path)) {
            abort(404, 'Archivo de firma no encontrado.');
        }

        // Recuperamos la ruta absoluta del sistema operativo para la transmisión
        $pathAbsoluto = Storage::disk('local')->path($authUser->signature_path);

        // Retornamos el archivo forzando el Content-Type correcto y políticas de caché seguras
        return response()->file($pathAbsoluto, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'private, max-age=86400, must-revalidate', // Almacena en caché local por 24 horas de forma privada
        ]);
    }

    /**
     * ESCANER COMPORTAMENTAL: Renderizar Sello de la Administración General
     * * Algoritmo de detección dinámica que busca en caliente el sello y la firma de la 
     * Administradora del plantel (`sello-firma-boletas`). Valida extensiones (.jpg, .jpeg, .png)
     * de forma secuencial y automatiza el Content-Type adecuado para el reporte oficial SUNAT R08.
     */
    public function renderAdminSignature()
    {
        // 1. Matriz de extensiones institucionales estándar admitidas
        $posiblesExtensiones = ['jpg', 'jpeg', 'png'];
        $pathAdmin = null;
        $mimeType = 'image/png'; // Formato por defecto del sistema

        // 2. Escaneo en caliente por descarte en el disco privado local
        foreach ($posiblesExtensiones as $ext) {
            $rutaPrueba = "firmas/sello-firma-boletas.{$ext}";
            
            // Si el driver local encuentra el archivo con la extensión en evaluación, guarda los metadatos
            if (Storage::disk('local')->exists($rutaPrueba)) {
                $pathAdmin = $rutaPrueba;
                // Sincronizamos el encabezado MIME real según el formato físico encontrado en el disco duro
                $mimeType = ($ext === 'png') ? 'image/png' : 'image/jpeg';
                break; // Rompemos el bucle inmediatamente al hallar la correspondencia física
            }
        }

        // 3. Control de contingencia técnica: Si el archivo fue removido o renombrado incorrectamente
        if (!$pathAdmin) {
            abort(404, 'Sello de administración no configurado en el storage privado.');
        }

        // 4. Resolvemos la ruta absoluta del sistema de archivos local de Laravel
        $pathAbsoluto = Storage::disk('local')->path($pathAdmin);

        // 5. Servimos el flujo binario puro directo hacia el Iframe o visor de la boleta
        return response()->file($pathAbsoluto, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'private, max-age=86400, must-revalidate',
        ]);
    }
}