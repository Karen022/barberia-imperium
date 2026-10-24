<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarberCommission;
use App\Services\CommissionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    public function index()
    {
        // Obtener los periodos de comisiones
        $periods = DB::table('commission_periods')
            ->orderByDesc('start_date')
            ->paginate(10);

        //Obtener las liquidaciones de los periodos encotnrados
        $commissions = DB::table('barber_commissions as bc')
            ->join('users as u', 'u.id', '=', 'bc.barber_id')
            ->whereIn('bc.commission_period_id', $periods->pluck('id'))
            ->select([
                'bc.*',
                'u.name as barber_name',
            ])
            ->selectSub(function ($query) {
                $query->from('commission_payment_allocations as cpa')
                    ->selectRaw('COALESCE(SUM(cpa.amount), 0)')
                    ->whereColumn('cpa.barber_commission_id', 'bc.id');
            }, 'total_paid')
            ->orderBy('u.name')
            ->get()
            ->groupBy('commission_period_id');

        //Enviar la informacion a la vista
        return view(
            'dashboard.admin.commissions.index',
            compact(
                'periods',
                'commissions'
            )
        );
    }

    public function show(BarberCommission $commission)
    {
        $commission->load([
            'barber',
            'period',
            'items.sale',
            'paymentAllocations.payment.registeredBy',
        ]);

        $availableCommissions = BarberCommission::query()
            ->with('period')
            ->withSum('paymentAllocations as total_paid', 'amount')
            ->where('barber_id', $commission->barber_id)
            ->whereHas('period', function ($query) {
                $query->where('status', 'closed');
            })->get()->filter(function ($item) {
                $pendingBalance = (float) $item->barber_amount
                    - (float) ($item->total_paid ?? 0);

                return $pendingBalance > 0;
            })
            ->sortBy('period.start_date')
            ->values();

        return view('dashboard.admin.commissions.show', compact('commission', 'availableCommissions'));
    }
    public function closeWeek(Request $request, CommissionService $commissionService)
    {
        // Validar la fecha recibida
        $validated = $request->validate([
            'date' => 'required|date',
        ]);

        try {
            // Solicitar el cierre semanal al servicio
            $period = $commissionService->closeWeek(
                Carbon::parse($validated['date']),
                (int) auth()->id()
            );

            // Mostrar mensaje de exito
            return redirect()
                ->route('dashboard.commissions.index')
                ->with('success', 'El período de comisiones se cerró correctamente.');
        } catch (\RuntimeException $e) {
            //Mostrar el motivo por el cual no pudo cerrarse
            return back()->withInput()->with('error', $e->getMessage());
        }
    }


}
