<?php

namespace App\Http\Controllers;

use App\Exceptions\OpenWeatherException;
use App\Models\Outdoor;
use App\Models\Sensor;
use App\Services\OpenWeatherService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index(OpenWeatherService $openWeather)
    {
        $latestSensor = Sensor::latest()->first();

        $suhu = $latestSensor?->temperature ?? 26;
        $kelembapan = $latestSensor?->humidity ?? 70;
        $thi = $latestSensor?->thi ?? null;
        $status = $latestSensor?->status ?? 'Belum tersedia';
        $last_update = $latestSensor?->created_at?->diffForHumans() ?? '—';
        $sensor_location = 'Kandang 1';
        $outdoor_error = null;

        try {
            $outdoor = $openWeather->latest();
        } catch (OpenWeatherException $exception) {
            $previous = $exception->getPrevious();
            $httpStatus = $previous instanceof RequestException
                ? $previous->response->status()
                : null;

            Log::warning('Dashboard OpenWeather update failed.', [
                'exception_type' => $previous ? $previous::class : $exception::class,
                'http_status' => $httpStatus,
            ]);

            $outdoor = Outdoor::query()->latest('created_at')->first();
            $outdoor_error = match ($httpStatus) {
                401 => 'OpenWeather menolak API key. Periksa key dan pastikan sudah aktif.',
                403 => 'Akun OpenWeather belum memiliki akses ke One Call API 4.0.',
                429 => 'Kuota request OpenWeather terlampaui. Coba lagi setelah kuota tersedia.',
                default => $previous instanceof ConnectionException
                    ? 'Server tidak dapat terhubung ke OpenWeather. Periksa koneksi internet server.'
                    : 'Data cuaca gagal diperbarui. Periksa konfigurasi dan log aplikasi.',
            };
        }

        $selisih_suhu = $latestSensor && $outdoor
            ? round($latestSensor->temperature - $outdoor->suhu, 1)
            : null;

        return view('welcome', [
            'suhu' => $suhu,
            'kelembapan' => $kelembapan,
            'thi' => $thi,
            'status' => $status,
            'last_update' => $last_update,
            'sensor_location' => $sensor_location,
            'ai_summary' => $status,
            'outdoor' => $outdoor,
            'outdoor_error' => $outdoor_error,
            'selisih_suhu' => $selisih_suhu,
        ]);
    }
}
