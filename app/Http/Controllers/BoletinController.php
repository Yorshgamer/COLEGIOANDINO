<?php

namespace App\Http\Controllers;

use App\Models\Boletin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;
use Inertia\Response;

class BoletinController extends Controller
{
    public function index(): Response
    {
        $boletines = Boletin::orderByDesc('fecha_publicacion')
            ->orderByDesc('id')
            ->paginate(10);

        return Inertia::render('Boletines/Index', [
            'boletines' => $boletines,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'numero'            => 'required|string|max:20|unique:boletines,numero',
            'titulo'            => 'required|string|max:255',
            'descripcion'       => 'nullable|string',
            'fecha_publicacion' => 'required|date',
            'pdf_file'          => 'required|file|mimes:pdf|max:20480',
        ], [
            'numero.unique' => 'El número de boletín ya se encuentra registrado. Usa una edición diferente.',
        ]);

        $file = $request->file('pdf_file');
        $numeroLimpio = preg_replace('/[^0-9]/', '', $validated['numero']) ?: $validated['numero'];
        $fileName = 'boletin_semana_' . str_pad($numeroLimpio, 2, '0', STR_PAD_LEFT) . '_' . time() . '.pdf';

        $destinationPath = public_path('archivos-boletines');
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        $file->move($destinationPath, $fileName);

        Boletin::create([
            'numero'            => $validated['numero'],
            'titulo'            => $validated['titulo'],
            'descripcion'       => $validated['descripcion'] ?? null,
            'fecha_publicacion' => $validated['fecha_publicacion'],
            'archivo_pdf'       => 'archivos-boletines/' . $fileName,
            'is_active'         => true,
        ]);

        return back()->with('success', 'Boletín publicado exitosamente.');
    }

    public function update(Request $request, Boletin $boletin)
    {
        $validated = $request->validate([
            'numero'            => 'required|string|max:20|unique:boletines,numero,' . $boletin->id,
            'titulo'            => 'required|string|max:255',
            'descripcion'       => 'nullable|string',
            'fecha_publicacion' => 'required|date',
            'pdf_file'          => 'nullable|file|mimes:pdf|max:20480', // Opcional al editar
        ], [
            'numero.unique' => 'El número de boletín ya está asignado a otra publicación.',
        ]);

        $data = [
            'numero'            => $validated['numero'],
            'titulo'            => $validated['titulo'],
            'descripcion'       => $validated['descripcion'] ?? null,
            'fecha_publicacion' => $validated['fecha_publicacion'],
        ];

        // Si se subió un nuevo PDF, se reemplaza el archivo físico
        if ($request->hasFile('pdf_file')) {
            $oldFilePath = public_path($boletin->archivo_pdf);
            if (File::exists($oldFilePath)) {
                File::delete($oldFilePath);
            }

            $file = $request->file('pdf_file');
            $numeroLimpio = preg_replace('/[^0-9]/', '', $validated['numero']) ?: $validated['numero'];
            $fileName = 'boletin_semana_' . str_pad($numeroLimpio, 2, '0', STR_PAD_LEFT) . '_' . time() . '.pdf';

            $destinationPath = public_path('archivos-boletines');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $fileName);
            $data['archivo_pdf'] = 'archivos-boletines/' . $fileName;
        }

        $boletin->update($data);

        return back()->with('success', 'Boletín actualizado correctamente.');
    }

    public function destroy(Boletin $boletin)
    {
        $filePath = public_path($boletin->archivo_pdf);
        if (File::exists($filePath)) {
            File::delete($filePath);
        }

        $boletin->delete();

        return back()->with('success', 'Boletín eliminado correctamente.');
    }
}
