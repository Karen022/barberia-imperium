<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }
    public function index()
    {
        $users = user::orderBy('name')->get();
        return view('users.index', compact('users'));
    }

    public function clients()
    {

        $clients = User::role('client')->get();

        return view('dashboard.clients.index', compact('clients'));
    }

    public function barbers()
    {

        $barbers = User::role('barber')->get();

        return view('dashboard.barbers.index', compact('barbers'));
    }

    public function createBarber()
    {
        return view('dashboard.barbers.create');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function storeBarber(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:25',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        $user->assignRole('barber');

        return redirect()->route('dashboard.barbers.index')
            ->with(
            'success',
            'Barbero creaado correctamente');
    }
    public function store(Request $request)
    {
        // Validación incluyendo role, ya que el admin decide
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:admin,barber,client',
        ]);

        // Crear usuario con hash de password
        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);

        // Asignar rol con Spatie
        if (method_exists($user, 'assignRole')) {
            $user->assignRole($validated['role']);
        }

        return redirect()->route('users.index')->with('success', 'Usuario creado correctamente!');
    }


    public function toggleStatus(User $user){
        $user->update([
            'status' => $user->status === 'active'
                ? 'inactive'
                : 'active',
        ]);

        return redirect()->route('dashboard.barbers.index')
            ->with('success', 'Estado del barbero actualizado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'role' => 'sometimes|required|in:admin,barber,client',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        if (method_exists($user, 'syncRoles')) {
            $user->syncRoles([$validated['role']]);
        }

        return redirect()->route('users.index')->with('success', 'Usuario actualizadp correctamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado correctamente!');
    }
}
