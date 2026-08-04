<?php

namespace App\Observers;

use App\Models\Service;
use App\Models\ServiceStatusLog;
use Illuminate\Support\Facades\Log;

class ServiceObserver
{
    public function creating(Service $service)
    {
        // Generate Automatic Ticket Number
        // Format: SRV-YYYYMMDD-001
        $date = now()->format('Ymd');
        $latestService = Service::whereDate('created_at', now()->toDateString())->orderBy('id', 'desc')->first();
        
        $number = 1;
        if ($latestService) {
            $lastTicket = $latestService->ticket_number;
            $parts = explode('-', $lastTicket);
            if(isset($parts[2])) {
                $number = intval($parts[2]) + 1;
            }
        }
        
        $service->ticket_number = 'SRV-' . $date . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    public function created(Service $service): void
    {
        // Initial Log
        ServiceStatusLog::create([
            'service_id' => $service->id,
            'old_status' => null,
            'new_status' => $service->status,
            'notes' => 'Servis didaftarkan',
            'changed_by' => auth()->id() ?? null,
        ]);
    }

    public function updated(Service $service): void
    {
        // Check if status is dirty (changed)
        if ($service->isDirty('status')) {
            $oldStatus = $service->getOriginal('status');
            $newStatus = $service->status;

            ServiceStatusLog::create([
                'service_id' => $service->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'notes' => 'Status diubah ke ' . $newStatus,
                'changed_by' => auth()->id() ?? null,
            ]);

            // WhatsApp Notification Trigger
            $notifyStatuses = ['pemeriksaan', 'menunggu_persetujuan', 'perbaikan', 'selesai'];
            
            if (in_array($newStatus, $notifyStatuses)) {
                $this->sendWhatsAppNotification($service);
            }
        }
    }
    
    private function sendWhatsAppNotification(Service $service)
    {
        // TODO: Implement actual WhatsApp API call (Fonnte, Wazzup, Twilio, etc)
        // For now, we just log it as a placeholder.
        
        $customerName = $service->customer->name;
        $customerWa = $service->customer->whatsapp;
        $status = ucfirst(str_replace('_', ' ', $service->status));
        
        $message = "Halo {$customerName}, status servis laptop Anda dengan tiket {$service->ticket_number} saat ini adalah *{$status}*. Silakan cek detailnya di website kami.";
        
        Log::info("WhatsApp Notification sent to {$customerWa}: {$message}");
    }
}
