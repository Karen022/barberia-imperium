<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Sale;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $today = now()->toDateString();

        $data = [
            // métricas globales (solo admin)
            'global' => [
                'appointmentsToday' => 0,
                'incomeToday' => 0,
                'clientsCount' => 0,
                'barbersCount' => 0,
                'lowStockProducts' => 0,
            ],

            // métricas personales (admin + barbero)
            'personal' => [
                'appointmentsToday' => 0,
                'monthlyAppointments' => 0,
                'incomeToday' => 0,
                'salesCount' => 0,
            ]
        ];

        /* MÉTRICAS GLOBALES */
        if ($user->hasRole('admin')) {
            $data['global'] = [
                'appointmentsToday' => Appointment::whereDate('scheduled_at', $today)->count(),
                'incomeToday' => Sale::whereDate('created_at', $today)->sum('total'),
                'clientsCount' => User::role('client')->count(),
                'barbersCount' => User::role('barber')->count(),
                'lowStockProducts' => Product::where('stock', '<=', 5)->count(),
            ];
        }

        /*  MÉTRICAS PERSONALES  */
        if ($user->hasAnyRole(['admin', 'barber'])) {
            $data['personal'] = [
                'appointmentsToday' => Appointment::where('barber_id', $user->id)
                    ->whereDate('scheduled_at', $today)
                    ->count(),

                'monthlyAppointments' => Appointment::where('barber_id', $user->id)
                    ->whereMonth('scheduled_at', now()->month)
                    ->count(),

                'incomeToday' => Sale::where('user_id', $user->id)
                    ->whereDate('created_at', $today)
                    ->sum('total'),

                'salesCount' => Sale::where('user_id', $user->id)->count(),
            ];
        }
        return view('dashboard.index', compact('data'));
    }
}
