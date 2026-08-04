<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'customer_id',
        'assigned_technician_id',
        'laptop_brand',
        'laptop_type',
        'serial_number',
        'equipment',
        'complaint',
        'diagnosis',
        'service_fee',
        'total_cost',
        'estimated_cost',
        'estimated_finish',
        'status',
        'date_received',
        'date_completed',
    ];

    protected $casts = [
        'estimated_finish' => 'date',
        'date_received' => 'datetime',
        'date_completed' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_technician_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(ServiceDetail::class);
    }

    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class);
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(ServiceStatusLog::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(ServicePhoto::class);
    }

    public static function generateTicketNumber(): string
    {
        $prefix = 'SRV-' . date('Ymd') . '-';
        $latest = self::where('ticket_number', 'LIKE', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if (!$latest) {
            return $prefix . '001';
        }

        $sequence = (int) substr($latest->ticket_number, -3);
        return $prefix . str_pad($sequence + 1, 3, '0', STR_PAD_LEFT);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'antrean' => 'Antrean',
            'pemeriksaan' => 'Pemeriksaan',
            'menunggu_persetujuan' => 'Menunggu Persetujuan',
            'perbaikan' => 'Perbaikan',
            'selesai' => 'Selesai',
            'diambil' => 'Sudah Diambil',
            'batal' => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'antrean' => 'bg-amber-100 text-amber-800 border-amber-300',
            'pemeriksaan' => 'bg-blue-100 text-blue-800 border-blue-300',
            'menunggu_persetujuan' => 'bg-purple-100 text-purple-800 border-purple-300',
            'perbaikan' => 'bg-cyan-100 text-cyan-800 border-cyan-300',
            'selesai' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'diambil' => 'bg-slate-100 text-slate-800 border-slate-300',
            'batal' => 'bg-rose-100 text-rose-800 border-rose-300',
            default => 'bg-slate-100 text-slate-800 border-slate-300',
        };
    }

    public function getWhatsappUrlAttribute(): ?string
    {
        if (!$this->customer || !$this->customer->whatsapp) {
            return null;
        }

        $phone = preg_replace('/[^0-9]/', '', $this->customer->whatsapp);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        $message = "Halo *" . $this->customer->name . "*,\n\nStatus perbaikan laptop Anda (*" . $this->laptop_brand . " " . $this->laptop_type . "*) dengan Tiket *" . $this->ticket_number . "* telah diperbarui menjadi *" . strtoupper($this->status_label) . "*.\n\nEstimasi Biaya: Rp " . number_format($this->total_cost > 0 ? $this->total_cost : $this->estimated_cost, 0, ',', '.') . "\nSilakan cek detail progres terbaru di website LaptopCare.\n\nTerima Kasih!";

        return "https://wa.me/" . $phone . "?text=" . urlencode($message);
    }
}
