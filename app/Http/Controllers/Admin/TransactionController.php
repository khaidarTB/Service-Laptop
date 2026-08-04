<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Service;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('payment_status');
        $search = $request->query('search');

        $transactions = Transaction::with(['service.customer'])
            ->when($status, function ($query, $status) {
                $query->where('payment_status', $status);
            })
            ->when($search, function ($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('invoice_number', 'like', "%{$search}%")
                      ->orWhereHas('service', function($s) use ($search) {
                          $s->where('ticket_number', 'like', "%{$search}%")
                            ->orWhereHas('customer', function($c) use ($search) {
                                $c->where('name', 'like', "%{$search}%");
                            });
                      });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.transactions.index', compact('transactions', 'status', 'search'));
    }

    public function create()
    {
        $servicesWithoutTransaction = Service::doesntHave('transaction')
            ->with('customer')
            ->orderBy('id', 'desc')
            ->get();

        $invoiceNumber = Transaction::generateInvoiceNumber();

        return view('admin.transactions.create', compact('servicesWithoutTransaction', 'invoiceNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id|unique:transactions,service_id',
            'amount_paid' => 'required|numeric|min:0',
            'payment_method' => 'required|in:Tunai,Transfer,QRIS',
            'payment_status' => 'required|in:Belum Bayar,DP,Lunas',
        ]);

        $service = Service::findOrFail($request->service_id);

        $transaction = Transaction::create([
            'invoice_number' => Transaction::generateInvoiceNumber(),
            'service_id' => $service->id,
            'amount_paid' => $request->amount_paid,
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_status,
            'transaction_date' => Carbon::now(),
        ]);

        return redirect()->route('admin.transactions.invoice', $transaction->id)
            ->with('success', 'Transaksi berhasil disimpan! Cetak faktur tagihan.');
    }

    public function edit(Transaction $transaction)
    {
        return view('admin.transactions.edit', compact('transaction'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'amount_paid' => 'required|numeric|min:0',
            'payment_method' => 'required|in:Tunai,Transfer,QRIS',
            'payment_status' => 'required|in:Belum Bayar,DP,Lunas',
        ]);

        $transaction->update([
            'amount_paid' => $request->amount_paid,
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_status,
            'transaction_date' => $request->payment_status === 'Lunas' ? Carbon::now() : $transaction->transaction_date,
        ]);

        return redirect()->route('admin.transactions.index')->with('success', 'Data Transaksi berhasil diperbarui!');
    }

    public function invoice(Transaction $transaction)
    {
        $transaction->load(['service.customer', 'service.details.sparepart', 'service.technician']);
        return view('admin.transactions.invoice', compact('transaction'));
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return redirect()->route('admin.transactions.index')->with('success', 'Transaksi berhasil dihapus!');
    }
}
