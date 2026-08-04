<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Customer;
use App\Models\Sparepart;
use App\Models\Service;
use App\Models\ServiceDetail;
use App\Models\Transaction;
use App\Models\ServiceStatusLog;
use App\Models\ServicePhoto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin Account
        $admin = User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@laptopcare.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Teknisi Accounts
        $budi = User::create([
            'name' => 'Budi Teknisi',
            'email' => 'budi@laptopcare.com',
            'password' => Hash::make('password'),
            'role' => 'teknisi',
        ]);

        $agus = User::create([
            'name' => 'Agus Teknisi',
            'email' => 'agus@laptopcare.com',
            'password' => Hash::make('password'),
            'role' => 'teknisi',
        ]);

        // 3. Customer Accounts & Profiles
        $custUser1 = User::create([
            'name' => 'Bambang Pamungkas',
            'email' => 'bambang@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        $cust1 = Customer::create([
            'user_id' => $custUser1->id,
            'name' => 'Bambang Pamungkas',
            'whatsapp' => '081234567890',
            'address' => 'Jl. Merdeka No. 45, Jakarta Selatan',
        ]);

        $custUser2 = User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        $cust2 = Customer::create([
            'user_id' => $custUser2->id,
            'name' => 'Siti Nurhaliza',
            'whatsapp' => '085712345678',
            'address' => 'Jl. Mawar No. 12, Bandung',
        ]);

        $custUser3 = User::create([
            'name' => 'Dian Sastrowardoyo',
            'email' => 'dian@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        $cust3 = Customer::create([
            'user_id' => $custUser3->id,
            'name' => 'Dian Sastrowardoyo',
            'whatsapp' => '089698765432',
            'address' => 'Jl. Anggrek No. 8, Surabaya',
        ]);

        $cust4 = Customer::create([
            'name' => 'Randi Pangalila',
            'whatsapp' => '082133445566',
            'address' => 'Jl. Gajah Mada No. 99, Yogyakarta',
        ]);

        // 4. Spareparts Catalog
        $sp1 = Sparepart::create([
            'part_name' => 'SSD M.2 NVMe 512GB Kingston',
            'description' => 'Kecepatan baca hingga 3500MB/s, garansi 3 tahun.',
            'image' => 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?w=400',
            'stock' => 15,
            'min_stock' => 5,
            'cost_price' => 450000,
            'selling_price' => 650000,
        ]);

        $sp2 = Sparepart::create([
            'part_name' => 'RAM DDR4 8GB SODIMM 3200MHz',
            'description' => 'RAM Laptop DDR4 High Speed SODIMM.',
            'image' => 'https://images.unsplash.com/photo-1562976540-1502c2145186?w=400',
            'stock' => 20,
            'min_stock' => 4,
            'cost_price' => 280000,
            'selling_price' => 420000,
        ]);

        $sp3 = Sparepart::create([
            'part_name' => 'LCD Display Panel 14.0 Slim FHD IPS 30 Pin',
            'description' => 'Layar laptop 14 inchi Full HD IPS visual tajam.',
            'image' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=400',
            'stock' => 3, // low stock alert demo
            'min_stock' => 5,
            'cost_price' => 700000,
            'selling_price' => 950000,
        ]);

        $sp4 = Sparepart::create([
            'part_name' => 'Pasta Thermal Arctic MX-4 High Performance',
            'description' => 'Compound pendingin prosessor premium penghantar panas maksimal.',
            'image' => 'https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea?w=400',
            'stock' => 30,
            'min_stock' => 5,
            'cost_price' => 45000,
            'selling_price' => 85000,
        ]);

        $sp5 = Sparepart::create([
            'part_name' => 'Baterai Original Asus ROG Strix G531',
            'description' => 'Baterai replacement Asus ROG Li-ion 66Wh.',
            'image' => 'https://images.unsplash.com/photo-1619725002198-6a689b72f41d?w=400',
            'stock' => 2, // low stock alert demo
            'min_stock' => 3,
            'cost_price' => 500000,
            'selling_price' => 750000,
        ]);

        $sp6 = Sparepart::create([
            'part_name' => 'Keyboard Original Lenovo ThinkPad T480',
            'description' => 'Keyboard laptop dengan backlight dan TrackPoint.',
            'image' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=400',
            'stock' => 8,
            'min_stock' => 2,
            'cost_price' => 250000,
            'selling_price' => 380000,
        ]);

        // 5. Sample Services
        // Service 1: Selesai & Lunas (Bambang)
        $srv1 = Service::create([
            'ticket_number' => 'SRV-' . date('Ymd') . '-001',
            'customer_id' => $cust1->id,
            'assigned_technician_id' => $budi->id,
            'laptop_brand' => 'Asus',
            'laptop_type' => 'ROG Strix G531',
            'serial_number' => 'SN-ASUS-998822',
            'equipment' => 'Laptop, Charger Original, Tas Laptop',
            'complaint' => 'Laptop cepat panas, lambat buka game, dan lemot saat booting.',
            'diagnosis' => 'Pasta thermal kering keras, fan kotor berdebu, dan butuh upgrade SSD NVMe 512GB.',
            'service_fee' => 150000,
            'total_cost' => 885000, // 150.000 + 650.000 (SSD) + 85.000 (Pasta)
            'estimated_cost' => 900000,
            'estimated_finish' => Carbon::now()->addDays(1),
            'status' => 'selesai',
            'date_received' => Carbon::now()->subDays(3),
            'date_completed' => Carbon::now()->subHours(5),
        ]);

        ServiceDetail::create([
            'service_id' => $srv1->id,
            'sparepart_id' => $sp1->id,
            'quantity' => 1,
            'price_at_time' => 650000,
            'subtotal' => 650000,
        ]);

        ServiceDetail::create([
            'service_id' => $srv1->id,
            'sparepart_id' => $sp4->id,
            'quantity' => 1,
            'price_at_time' => 85000,
            'subtotal' => 85000,
        ]);

        Transaction::create([
            'invoice_number' => 'INV-' . date('Ymd') . '-001',
            'service_id' => $srv1->id,
            'amount_paid' => 885000,
            'payment_method' => 'QRIS',
            'payment_status' => 'Lunas',
            'transaction_date' => Carbon::now()->subHours(4),
        ]);

        ServiceStatusLog::create([
            'service_id' => $srv1->id,
            'old_status' => null,
            'new_status' => 'antrean',
            'notes' => 'Unit laptop diterima di front desk.',
            'changed_by' => $admin->id,
            'created_at' => Carbon::now()->subDays(3),
        ]);

        ServiceStatusLog::create([
            'service_id' => $srv1->id,
            'old_status' => 'antrean',
            'new_status' => 'pemeriksaan',
            'notes' => 'Teknisi Budi membongkar casing dan mengecek jalur motherboard.',
            'changed_by' => $budi->id,
            'created_at' => Carbon::now()->subDays(2),
        ]);

        ServiceStatusLog::create([
            'service_id' => $srv1->id,
            'old_status' => 'pemeriksaan',
            'new_status' => 'perbaikan',
            'notes' => 'Pemasangan SSD baru 512GB dan pembersihan fan thermal repaste.',
            'changed_by' => $budi->id,
            'created_at' => Carbon::now()->subDay(),
        ]);

        ServiceStatusLog::create([
            'service_id' => $srv1->id,
            'old_status' => 'perbaikan',
            'new_status' => 'selesai',
            'notes' => 'Running test benchmark 2 jam lancar tanpa overheated.',
            'changed_by' => $budi->id,
            'created_at' => Carbon::now()->subHours(5),
        ]);

        ServicePhoto::create([
            'service_id' => $srv1->id,
            'image' => 'https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea?w=600',
            'description' => 'Kondisi thermal paste kering keras sebelum dibersihkan.',
            'photo_type' => 'before',
        ]);

        ServicePhoto::create([
            'service_id' => $srv1->id,
            'image' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=600',
            'description' => 'Hasil perbaikan dan pemasangan SSD baru berjalan lancar.',
            'photo_type' => 'after',
        ]);

        // Service 2: Perbaikan (Siti)
        $srv2 = Service::create([
            'ticket_number' => 'SRV-' . date('Ymd') . '-002',
            'customer_id' => $cust2->id,
            'assigned_technician_id' => $agus->id,
            'laptop_brand' => 'Lenovo',
            'laptop_type' => 'ThinkPad T480',
            'serial_number' => 'SN-LNV-445511',
            'equipment' => 'Unit Laptop',
            'complaint' => 'Layar bergaris-garis dan beberapa tombol keyboard tidak bisa ditekan.',
            'diagnosis' => 'Layar LCD fleksibel rusak & keyboard perlu ganti unit baru.',
            'service_fee' => 100000,
            'total_cost' => 1430000, // 100.000 + 950.000 (LCD) + 380.000 (Keyboard)
            'estimated_cost' => 1450000,
            'estimated_finish' => Carbon::now()->addDays(2),
            'status' => 'perbaikan',
            'date_received' => Carbon::now()->subDays(1),
        ]);

        ServiceDetail::create([
            'service_id' => $srv2->id,
            'sparepart_id' => $sp3->id,
            'quantity' => 1,
            'price_at_time' => 950000,
            'subtotal' => 950000,
        ]);

        ServiceDetail::create([
            'service_id' => $srv2->id,
            'sparepart_id' => $sp6->id,
            'quantity' => 1,
            'price_at_time' => 380000,
            'subtotal' => 380000,
        ]);

        Transaction::create([
            'invoice_number' => 'INV-' . date('Ymd') . '-002',
            'service_id' => $srv2->id,
            'amount_paid' => 500000,
            'payment_method' => 'Transfer',
            'payment_status' => 'DP',
            'transaction_date' => Carbon::now()->subHours(10),
        ]);

        ServiceStatusLog::create([
            'service_id' => $srv2->id,
            'old_status' => null,
            'new_status' => 'antrean',
            'notes' => 'Pendaftaran unit lewat booking online.',
            'changed_by' => $admin->id,
            'created_at' => Carbon::now()->subDays(1),
        ]);

        ServiceStatusLog::create([
            'service_id' => $srv2->id,
            'old_status' => 'antrean',
            'new_status' => 'pemeriksaan',
            'notes' => 'Pengecekan fleksibel layar LCD & testing keyboard.',
            'changed_by' => $agus->id,
            'created_at' => Carbon::now()->subHours(18),
        ]);

        ServiceStatusLog::create([
            'service_id' => $srv2->id,
            'old_status' => 'pemeriksaan',
            'new_status' => 'perbaikan',
            'notes' => 'Proses pergantian komponen LCD panel dan keyboard ThinkPad.',
            'changed_by' => $agus->id,
            'created_at' => Carbon::now()->subHours(8),
        ]);

        // Service 3: Menunggu Persetujuan (Dian)
        $srv3 = Service::create([
            'ticket_number' => 'SRV-' . date('Ymd') . '-003',
            'customer_id' => $cust3->id,
            'assigned_technician_id' => $budi->id,
            'laptop_brand' => 'Acer',
            'laptop_type' => 'Swift 3',
            'serial_number' => 'SN-ACR-778899',
            'equipment' => 'Unit Laptop & Charger',
            'complaint' => 'Laptop tidak bisa dicharge dan baterai gembung.',
            'diagnosis' => 'Port IC Power charging konslet & Baterai gembung perlu penggantian.',
            'service_fee' => 200000,
            'total_cost' => 950000,
            'estimated_cost' => 950000,
            'estimated_finish' => Carbon::now()->addDays(3),
            'status' => 'menunggu_persetujuan',
            'date_received' => Carbon::now()->subHours(12),
        ]);

        ServiceStatusLog::create([
            'service_id' => $srv3->id,
            'old_status' => null,
            'new_status' => 'antrean',
            'notes' => 'Unit diserahkan customer di workshop.',
            'changed_by' => $admin->id,
            'created_at' => Carbon::now()->subHours(12),
        ]);

        ServiceStatusLog::create([
            'service_id' => $srv3->id,
            'old_status' => 'antrean',
            'new_status' => 'menunggu_persetujuan',
            'notes' => 'Diagnosa selesai. Mengirim rincian estimasi biaya ke WhatsApp pelanggan.',
            'changed_by' => $budi->id,
            'created_at' => Carbon::now()->subHours(4),
        ]);
    }
}
