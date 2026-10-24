<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with('user')
            ->latest('expense_date')
            ->latest()
            ->get();

        return view('dashboard.admin.expenses.index', compact('expenses'));
    }

    public function create()
    {
        return view('dashboard.admin.expenses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'expense_date' => ['required', 'date'],
            'concept' => ['required', 'string', 'max:255'],
            'category' => [
                'required',
                'string',
                'in:' . implode(',', [
                    'Personal',
                    'Servicios y alquiler',
                    'Insumos',
                    'Limpieza e higiene',
                    'Equipamiento',
                    'Mantenimiento y reparaciones',
                    'Administración',
                    'Publicidad y marketing',
                    'Otros',
                ]),
            ],
            'amount' => ['required', 'numeric', 'min:1'],
            'payment_method' => [
                'required',
                'string',
                'in:Efectivo,Transferencia,QR,En Línea',
            ],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $validated['user_id'] = auth()->id();

        Expense::create($validated);

        return redirect()->route('dashboard.expenses.index')->with('success', 'Egreso registrado correctamente.');
    }

    public function edit(Expense $expense)
    {
        return view('dashboard.admin.expenses.edit', compact('expense'));
    }
    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'expense_date' => ['required', 'date'],
            'concept' => ['required', 'string', 'max:255'],
            'category' => [
                'required',
                'string',
                'in:' . implode(',', [
                    'Personal',
                    'Servicios y alquiler',
                    'Insumos',
                    'Limpieza e higiene',
                    'Equipamiento',
                    'Mantenimiento y reparaciones',
                    'Administración',
                    'Publicidad y marketing',
                    'Otros',
                ]),
            ],
            'amount' => ['required', 'numeric', 'min:1'],
            'payment_method' => [
                'required',
                'string',
                'in:Efectivo,Transferencia,QR,En Línea',
            ],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $expense->update($validated);

        return redirect()->route('dashboard.expenses.index')->with('success', 'Egreso editado correctamente.');
    }
    public function cancel(Expense $expense)
    {
        $expense->update([
            'status' => 'cancelled',
        ]);

        return redirect()->route('dashboard.expenses.index')->with('success', 'Egreso anulado correctamente.');
    }

}
