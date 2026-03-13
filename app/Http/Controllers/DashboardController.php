<?php

namespace App\Http\Controllers;

use App\Models\FinancialPlan;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Summary this month
        $totalIncome = Transaction::where('user_id', $user->id)
            ->where('type', 'income')
            ->whereMonth('transaction_date', $currentMonth)
            ->whereYear('transaction_date', $currentYear)
            ->sum('amount');

        $totalExpense = Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->whereMonth('transaction_date', $currentMonth)
            ->whereYear('transaction_date', $currentYear)
            ->sum('amount');

        $balance = $totalIncome - $totalExpense;

        // 5 recent transactions
        $recentTransactions = Transaction::where('user_id', $user->id)
            ->with('category')
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Monthly income & expense for the last 6 months (for chart)
        $months = collect(range(5, 0))->map(function ($i) {
            return now()->subMonths($i);
        });

        $monthlyData = $months->map(function ($month) use ($user) {
            $income = Transaction::where('user_id', $user->id)
                ->where('type', 'income')
                ->whereMonth('transaction_date', $month->month)
                ->whereYear('transaction_date', $month->year)
                ->sum('amount');

            $expense = Transaction::where('user_id', $user->id)
                ->where('type', 'expense')
                ->whereMonth('transaction_date', $month->month)
                ->whereYear('transaction_date', $month->year)
                ->sum('amount');

            return [
                'month' => $month->translatedFormat('M Y'),
                'income' => (float) $income,
                'expense' => (float) $expense,
            ];
        });

        // Financial plan widget
        $financialPlan = FinancialPlan::firstOrCreate(
            ['user_id' => $user->id],
            ['saving_percent' => 30, 'investment_percent' => 20, 'spending_percent' => 50]
        );
        $planAllocations = $financialPlan->calculate((float) max(0, $totalIncome - $totalExpense));

        return view('dashboard.index', compact(
            'totalIncome',
            'totalExpense',
            'balance',
            'recentTransactions',
            'monthlyData',
            'financialPlan',
            'planAllocations'
        ));
    }
}
