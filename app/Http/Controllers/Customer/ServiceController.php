<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceApproval;
use App\Models\Transaction;
use App\Models\ServiceComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ServiceController extends Controller
{
    public function trackForm()
    {
        return view('customer.track');
    }

    public function track(Request $request)
    {
        $request->validate([
            'ticket_number' => 'required|string',
        ]);

        $query = trim($request->ticket_number);

        $service = Service::where('ticket_number', $query)
            ->orWhereHas('customer', function($q) use ($query) {
                $q->where('whatsapp', $query);
            })
            ->with(['customer', 'technician', 'details.sparepart', 'statusLogs.user', 'photos', 'transaction', 'comments.user', 'latestApproval'])
            ->latest()
            ->first();

        if (!$service) {
            return redirect()->route('customer.trackForm')
                ->with('error', 'Nomor Tiket "' . $query . '" tidak ditemukan. Silakan periksa kembali nomor tiket atau nomor WhatsApp Anda.');
        }

        return view('customer.track_result', compact('service'));
    }

    public function show(Service $service)
    {
        $service->load(['customer', 'technician', 'details.sparepart', 'statusLogs.user', 'photos', 'transaction', 'comments.user', 'latestApproval']);
        return view('customer.track_result', compact('service'));
    }

    public function approveForm(Service $service)
    {
        $service->load(['customer', 'technician', 'details.sparepart', 'latestApproval']);

        if ($service->status !== 'menunggu_persetujuan') {
            return redirect()->back()->with('error', 'Tiket ini tidak sedang menunggu persetujuan.');
        }

        if ($service->latestApproval) {
            return redirect()->back()->with('error', 'Anda sudah memberikan keputusan untuk tiket ini.');
        }

        return view('customer.approve', compact('service'));
    }

    public function approve(Request $request, Service $service)
    {
        if ($service->status !== 'menunggu_persetujuan') {
            return redirect()->back()->with('error', 'Tiket ini tidak sedang menunggu persetujuan.');
        }

        if ($service->latestApproval) {
            return redirect()->back()->with('error', 'Anda sudah memberikan keputusan untuk tiket ini.');
        }

        $request->validate([
            'decision' => 'required|in:approved,rejected',
            'note' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($service, $request) {
            ServiceApproval::create([
                'service_id' => $service->id,
                'user_id' => auth()->id(),
                'decision' => $request->decision,
                'approved_amount' => $request->decision === 'approved' ? $service->total_cost : null,
                'note' => $request->note,
            ]);

            if ($request->decision === 'approved') {
                $service->update(['status' => 'perbaikan']);

                Transaction::create([
                    'invoice_number' => Transaction::generateInvoiceNumber(),
                    'service_id' => $service->id,
                    'amount_paid' => 0,
                    'payment_method' => 'QRIS',
                    'payment_status' => 'Belum Bayar',
                ]);
            } else {
                $service->update(['status' => 'batal']);
            }
        });

        $msg = $request->decision === 'approved'
            ? 'Perbaikan disetujui! Teknisi akan segera memulai perbaikan.'
            : 'Perbaikan dibatalkan. Silakan ambil unit Anda.';

        return redirect()->route('customer.services.show', $service->id)->with('success', $msg);
    }

    public function createPayment(Service $service)
    {
        $service->load(['customer', 'details.sparepart', 'transaction']);

        if (!$service->transaction || $service->transaction->payment_status === 'Lunas') {
            return redirect()->back()->with('error', 'Tidak ada tagihan yang perlu dibayar.');
        }

        $clientKey = config('services.payment.midtrans.client_key');
        $isProduction = config('services.payment.midtrans.is_production');

        return view('customer.payment', compact('service', 'clientKey', 'isProduction'));
    }

    public function paymentCallback(Request $request)
    {
        $payload = $request->all();
        $serviceId = $payload['service_id'] ?? null;

        if (!$serviceId) {
            return response()->json(['status' => 'error'], 400);
        }

        $service = Service::find($serviceId);
        if (!$service || !$service->transaction) {
            return response()->json(['status' => 'error'], 404);
        }

        $transactionStatus = $payload['transaction_status'] ?? '';
        $statusCode = $payload['status_code'] ?? '';

        DB::transaction(function () use ($service, $transactionStatus, $statusCode, $payload) {
            if ($transactionStatus === 'capture' || $transactionStatus === 'settlement' || $statusCode === '200') {
                $service->transaction->update([
                    'payment_status' => 'Lunas',
                    'amount_paid' => $payload['gross_amount'] ?? $service->total_cost,
                    'payment_method' => 'QRIS',
                    'transaction_date' => Carbon::now(),
                ]);

                $service->update(['status' => 'selesai']);
            } elseif (in_array($transactionStatus, ['pending', 'capture'])) {
                $service->transaction->update([
                    'payment_status' => 'DP',
                    'amount_paid' => $payload['gross_amount'] ?? 0,
                    'payment_method' => 'QRIS',
                    'transaction_date' => Carbon::now(),
                ]);
            }
        });

        return response()->json(['status' => 'ok']);
    }

    public function addComment(Request $request, Service $service)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        ServiceComment::create([
            'service_id' => $service->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
            'is_internal' => false,
        ]);

        return redirect()->back()->with('success', 'Pesan berhasil dikirim!');
    }
}
