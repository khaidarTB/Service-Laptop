<?php

namespace App\Observers;

use App\Models\Service;
use App\Models\ServiceStatusLog;
use Illuminate\Support\Facades\Log;

class ServiceObserver
{
    public function creating(Service $service): void
    {
        $date = now()->format('Ymd');
        $latestService = Service::whereDate('created_at', now()->toDateString())->orderBy('id', 'desc')->first();
        
        $number = 1;
        if ($latestService) {
            $lastTicket = $latestService->ticket_number;
            $parts = explode('-', $lastTicket);
            if (isset($parts[2])) {
                $number = intval($parts[2]) + 1;
            }
        }
        
        $service->ticket_number = 'SRV-' . $date . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    public function created(Service $service): void
    {
        ServiceStatusLog::create([
            'service_id' => $service->id,
            'old_status' => null,
            'new_status' => $service->status,
            'notes' => 'Servis didaftarkan',
            'changed_by' => auth()->id() ?? null,
        ]);

        $notifyStatuses = ['antrean'];
        if (in_array($service->status, $notifyStatuses)) {
            $this->sendWhatsAppNotification($service, 'booking_confirmed');
        }
    }

    public function updated(Service $service): void
    {
        if ($service->isDirty('status')) {
            $oldStatus = $service->getOriginal('status');
            $newStatus = $service->status;

            $notifyStatuses = ['pemeriksaan', 'menunggu_persetujuan', 'perbaikan', 'selesai'];
            
            if (in_array($newStatus, $notifyStatuses)) {
                $this->sendWhatsAppNotification($service, 'status_update');
            }
        }
    }
    
    private function sendWhatsAppNotification(Service $service, string $type = 'status_update'): void
    {
        $apiUrl = config('services.whatsapp.api_url');
        $apiKey = config('services.whatsapp.api_key');

        if (!$apiKey || !$service->customer || !$service->customer->whatsapp) {
            Log::info('WhatsApp API not configured or no customer phone', ['service_id' => $service->id, 'type' => $type]);
            return;
        }

        $phone = $service->getPhone();
        $message = $service->getNotificationMessage();

        try {
            \Illuminate\Support\Facades\Http::timeout(10)
                ->withHeaders([
                    'Authorization' => $apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($apiUrl, [
                    'target' => $phone,
                    'message' => $message,
                    'typing' => true,
                ]);
            
            Log::info('WhatsApp notification sent', ['service_id' => $service->id, 'phone' => $phone, 'type' => $type]);
        } catch (\Exception $e) {
            Log::error('WhatsApp API failed', ['service_id' => $service->id, 'error' => $e->getMessage()]);
        }
    }
}
