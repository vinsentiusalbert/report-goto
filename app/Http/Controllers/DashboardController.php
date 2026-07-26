<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $summary = [
            ['label' => 'Total Campaign', 'value' => '148', 'change' => '+12,5%', 'tone' => 'green', 'icon' => 'route'],
            ['label' => 'Total Spending', 'value' => 'Rp 184,2 jt', 'change' => '+8,2%', 'tone' => 'blue', 'icon' => 'wallet'],
            ['label' => 'Audience Reached', 'value' => '1,28 jt', 'change' => '+18,4%', 'tone' => 'purple', 'icon' => 'users'],
            ['label' => 'Delivery Rate', 'value' => '97,8%', 'change' => '+0,8%', 'tone' => 'orange', 'icon' => 'star'],
        ];

        $activities = [
            ['id' => 'CMP-2607-0148', 'service' => 'SMS', 'driver' => 'Promo Payday GoFood', 'time' => '25 Jul 2026', 'amount' => '125.480', 'status' => 'Aktif'],
            ['id' => 'CMP-2607-0147', 'service' => 'MMS', 'driver' => 'Awareness Merchant Baru', 'time' => '24 Jul 2026', 'amount' => '82.150', 'status' => 'Selesai'],
            ['id' => 'CMP-2607-0146', 'service' => 'USSD', 'driver' => 'Voucher GoRide Jakarta', 'time' => '24 Jul 2026', 'amount' => '64.720', 'status' => 'Aktif'],
            ['id' => 'CMP-2607-0145', 'service' => 'SMS', 'driver' => 'Retargeting Pelanggan', 'time' => '23 Jul 2026', 'amount' => '48.900', 'status' => 'Selesai'],
            ['id' => 'CMP-2607-0144', 'service' => 'MMS', 'driver' => 'Promo GoSend UMKM', 'time' => '22 Jul 2026', 'amount' => '31.250', 'status' => 'Dihentikan'],
        ];

        return view('dashboard', compact('summary', 'activities'));
    }
}
