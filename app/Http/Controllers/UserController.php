<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\CredencialesCreadasNotification;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Iniciamos la consulta incluyendo eliminados (soft deletes) y sus roles
        $query = User::withTrashed()->with('roles');

        // 🔍 1. FILTRO DE BÚSQUEDA GLOBAL (Nombre, Email o DNI)
        if ($request->filled('search')) {
            $term = trim($request->input('search'));

            $query->where(function ($q) use ($term) {
                $q->where('first_name', 'LIKE', "%{$term}%")
                    ->orWhere('paternal_surname', 'LIKE', "%{$term}%")
                    ->orWhere('maternal_surname', 'LIKE', "%{$term}%")
                    ->orWhere('email', 'LIKE', "%{$term}%")
                    ->orWhere('dni', 'LIKE', "%{$term}%");
            });
        }

        // 🎭 2. FILTRO POR ROL
        if ($request->filled('role') && $request->input('role') !== 'all') {
            $query->role($request->input('role')); // Si usas Spatie, o ->whereHas('roles', ...)
        }

        // 🚦 3. FILTRO POR ESTADO (Activo, Inactivo, Baja)
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $status = $request->input('status');

            if ($status === 'Baja') {
                $query->onlyTrashed(); // Solo muestra los usuarios con soft delete
            } else {
                $query->whereNull('deleted_at')->where('status', $status);
            }
        }

        // Paginamos e inyectamos la Query String para mantener los filtros vivos al cambiar de página
        $users = $query->orderBy('first_name', 'asc')
            ->paginate(20)
            ->onEachSide(3)
            ->withQueryString();

        return Inertia::render('Users/Index', [
            'users' => $users,
            'filters' => $request->only(['search', 'role', 'status']),
            // Enviamos todos los nombres de roles para el select del cliente
            'allRoles' => Role::orderBy('name', 'asc')->pluck('name')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return Inertia::render('Users/Create', [
            'roles' => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'paternal_surname' => 'required|string|max:255',
            'maternal_surname' => 'required|string|max:255',
            'dni' => 'required|string|size:8|unique:users,dni',
            'phone' => 'nullable|string|max:15',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|string',
            'status' => 'required|in:Activo,Inactivo'
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'paternal_surname' => $request->paternal_surname,
            'maternal_surname' => $request->maternal_surname,
            'dni' => $request->dni,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'status' => $request->status,
        ]);

        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', 'Usuario creado.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Cargamos las boletas del usuario ordenadas desde la más reciente
        $user->load(['boletas' => function ($query) {
            $query->orderBy('anio', 'desc')->orderBy('mes', 'desc');
        }]);

        return Inertia::render('Users/Show', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'dni' => $user->dni,
                'phone' => $user->phone,
                'email' => $user->email,
                'status' => $user->status,
                'created_at' => $user->created_at->toIso8601String(),
                'roles' => $user->roles,
                'boletas' => $user->boletas // 🚀 Pasamos la colección al frontend
            ],
            'flash' => [
                'password_generada' => session('password_generada')
            ]
        ]);
    }

    public function regenerarCredenciales(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            // 1. Generamos una clave segura aleatoria de 12 caracteres (letras, números y símbolos)
            $passwordTemporal = Str::password(12);

            // 2. Actualizamos la Base de Datos encriptando la nueva clave
            $user->update([
                'password' => Hash::make($passwordTemporal)
            ]);

            // 3. Despachamos la notificación al correo del docente
            $user->notify(new CredencialesCreadasNotification($passwordTemporal));

            // 4. Retornamos guardando la clave en la sesión flash. 
            // Inertia compartirá automáticamente este valor con Vue en la recarga inmediata.
            return back()->with([
                'success' => 'Nuevas credenciales generadas y enviadas al correo institucional con éxito.',
                'password_generada' => $passwordTemporal // 👈 Clave efímera en texto plano
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al regenerar credenciales: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return Inertia::render('Users/Edit', [
            'user' => $user->load('roles'),
            'roles' => Role::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Añadimos la regla para password como opcional (nullable)
        $request->validate([
            'first_name' => 'required|string|max:255',
            'paternal_surname' => 'required|string|max:255',
            'maternal_surname' => 'required|string|max:255',
            'dni' => 'required|string|size:8|unique:users,dni,' . $user->id,
            'phone' => 'nullable|string|max:15',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|string',
            'status' => 'required|in:Activo,Inactivo',
            'password' => 'nullable|string|min:6' // <-- Regla nullable añadida
        ]);

        // Armamos el lote de actualización base
        $updateData = [
            'first_name' => $request->first_name,
            'paternal_surname' => $request->paternal_surname,
            'maternal_surname' => $request->maternal_surname,
            'dni' => $request->dni,
            'phone' => $request->phone,
            'email' => $request->email,
            'status' => $request->status,
        ];

        // CONDICIONAL CRÍTICA: Si el administrador digitó una contraseña, la encriptamos y la sumamos al lote
        if ($request->filled('password')) {
            $updateData['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        // Ejecutamos la actualización atómica
        $user->update($updateData);

        // Sincronizar el nuevo rol de Spatie de forma limpia
        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')->with('success', 'Usuario y credenciales actualizados con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado.');
    }

    /**
     * Método nuevo: Dar de Alta / Restaurar un trabajador devuelto
     */
    public function restore($id)
    {
        // Buscamos específicamente en la papelera de reciclaje lógica
        $user = User::onlyTrashed()->findOrFail($id);

        // Restauramos el usuario (deleted_at vuelve a ser NULL)
        $user->restore();

        // Opcional: Nos aseguramos de que su estado operativo vuelva a ser 'Activo'
        $user->update(['status' => 'Activo']);

        return redirect()->route('users.index')->with('success', 'Trabajador reincorporado y dado de alta en el sistema.');
    }
}
