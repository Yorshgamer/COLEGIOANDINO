<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileSignatureController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'signature' => 'required|string'
        ]);

        /** @var User $user */
        $user = Auth::user();

        // 1. Eliminamos la firma anterior si existía para evitar acumular basura en el disco
        if ($user->signature_path) {
            Storage::disk('public')->delete($user->signature_path);
        }

        // 2. Procesamos la cadena binaria Base64 proveniente de Vue
        $imageData = $request->signature;
        $image = str_replace('data:image/png;base64,', '', $imageData);
        $image = str_replace(' ', '+', $image);

        // 3. Generamos un nombre ultra profesional: ej. "firma_juan_perez_74859612.png"
        // Str::slug convierte "Yorshyo Artemio" en "yorshyo-artemio" de forma segura para URLs y sistemas de archivos
        $employeeSlug = Str::slug($user->first_name . '_' . $user->paternal_surname);
        $imageName = 'signatures/firma_' . $employeeSlug . '_' . $user->dni . '.png';

        // 4. Guardamos el archivo físicamente en el disco
        Storage::disk('public')->put($imageName, base64_decode($image));

        // 5. Actualizamos el registro en la base de datos
        $user->update([
            'signature_path' => $imageName
        ]);

        return back()->with('success', 'Firma institucional registrada correctamente.');
    }
}
