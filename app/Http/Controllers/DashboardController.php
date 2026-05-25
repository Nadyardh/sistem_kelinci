<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data sensor terakhir
        $latestSensor = Sensor::latest()->first();

        // Default values jika tidak ada data
        $suhu = $latestSensor?->temperature ?? 26;
        $kelembapan = $latestSensor?->humidity ?? 70;
        $thi = $latestSensor?->thi ?? null;
        $status = $latestSensor?->status ?? 'Belum tersedia';
        $last_update = $latestSensor?->created_at?->diffForHumans() ?? '—';
        $sensor_location = 'Kandang 1'; // Bisa disesuaikan sesuai kebutuhan

        return view('welcome', [
            'suhu' => $suhu,
            'kelembapan' => $kelembapan,
            'thi' => $thi,
            'status' => $status,
            'last_update' => $last_update,
            'sensor_location' => $sensor_location,
            'ai_summary' => $status
        ]);
    }
}
