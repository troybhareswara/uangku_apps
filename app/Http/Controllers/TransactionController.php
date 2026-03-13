<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Transaction::where('user_id', $user->id)->with('category');

        if ($request->type) {
            $query->where('type', $request->type);
        }
        if ($request->month) {
            $query->whereMonth('transaction_date', $request->month);
        }
        if ($request->year) {
            $query->whereYear('transaction_date', $request->year);
        } else {
            $query->whereYear('transaction_date', now()->year);
        }
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $transactions = $query->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $categories = Category::where('user_id', $user->id)->get();

        $totalIncome = $query->clone()->where('type', 'income')->sum('amount');
        $totalExpense = $query->clone()->where('type', 'expense')->sum('amount');

        return view('transactions.index', compact('transactions', 'categories', 'totalIncome', 'totalExpense'));
    }

    public function create()
    {
        $categories = Category::where('user_id', Auth::id())->get();
        return view('transactions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'      => 'required|exists:categories,id',
            'type'             => 'required|in:income,expense',
            'amount'           => 'required|numeric|min:1',
            'description'      => 'nullable|string|max:255',
            'transaction_date' => 'required|date',
        ]);

        // Ensure category belongs to user
        $category = Category::where('id', $validated['category_id'])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        Transaction::create([
            ...$validated,
            'user_id' => Auth::id(),
            'type'    => $category->type,
        ]);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil ditambahkan!');
    }

    public function edit(Transaction $transaction)
    {
        $this->authorize('update', $transaction);
        $categories = Category::where('user_id', Auth::id())->get();
        return view('transactions.edit', compact('transaction', 'categories'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $validated = $request->validate([
            'category_id'      => 'required|exists:categories,id',
            'amount'           => 'required|numeric|min:1',
            'description'      => 'nullable|string|max:255',
            'transaction_date' => 'required|date',
        ]);

        $category = Category::where('id', $validated['category_id'])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $transaction->update([
            ...$validated,
            'type' => $category->type,
        ]);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil diperbarui!');
    }

    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);
        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil dihapus!');
    }
}
