<?php

namespace App\Http\Controllers;

use App\Models\FinancialPlan;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinancialPlanController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Auto-create default plan if none exists
        $plan = FinancialPlan::firstOrCreate(
            ['user_id' => $user->id],
            [
                'saving_percent'     => 30,
                'investment_percent' => 20,
                'spending_percent'   => 50,
            ]
        );

        // Calculate total balance (all time income - expense)
        $totalIncome  = Transaction::where('user_id', $user->id)->where('type', 'income')->sum('amount');
        $totalExpense = Transaction::where('user_id', $user->id)->where('type', 'expense')->sum('amount');
        $totalBalance = max(0, $totalIncome - $totalExpense);

        // This month
        $monthIncome  = Transaction::where('user_id', $user->id)->where('type', 'income')->thisMonth()->sum('amount');
        $monthExpense = Transaction::where('user_id', $user->id)->where('type', 'expense')->thisMonth()->sum('amount');
        $monthBalance = max(0, $monthIncome - $monthExpense);

        $allocations      = $plan->calculate($totalBalance);
        $monthAllocations = $plan->calculate($monthBalance);

        return view('investasi.index', compact(
            'plan',
            'totalBalance',
            'monthBalance',
            'allocations',
            'monthAllocations',
            'totalIncome',
            'totalExpense',
            'monthIncome',
            'monthExpense'
        ));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'saving_percent'     => 'required|numeric|min:0|max:100',
            'investment_percent' => 'required|numeric|min:0|max:100',
            'spending_percent'   => 'required|numeric|min:0|max:100',
        ]);

        $total = $validated['saving_percent']
               + $validated['investment_percent']
               + $validated['spending_percent'];

        if (round($total, 2) !== 100.00) {
            return back()
                ->withInput()
                ->withErrors(['total' => "Total persentase harus 100%. Saat ini: {$total}%"]);
        }

        $plan = FinancialPlan::firstOrCreate(['user_id' => Auth::id()]);
        $plan->update($validated);

        return redirect()->route('investasi.index')
            ->with('success', 'Rencana keuangan berhasil disimpan!');
    }
}
