<?php

namespace Tests\Feature;

use App\Models\Sensor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OutdoorWeatherTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_stores_and_displays_openweather_data_and_temperature_difference(): void
    {
        config([
            'services.openweather.key' => 'test-key',
            'services.openweather.lat' => '-6.2',
            'services.openweather.lon' => '106.8',
        ]);

        Sensor::query()->create([
            'temperature' => 33.5,
            'humidity' => 70,
            'thi' => 30,
            'status' => 'Mild Stress',
        ]);

        Http::fake([
            'https://api.openweathermap.org/data/4.0/onecall/timeline/15min*' => Http::response([
                'data' => [[
                    'dt' => now()->timestamp,
                    'temp' => 31.2,
                    'humidity' => 68.5,
                    'weather' => [[
                        'main' => 'Rain',
                        'description' => 'hujan ringan',
                    ]],
                ]],
            ]),
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Informasi Data Terbaru')
            ->assertSee('Data Kandang')
            ->assertSee('Data Cuaca')
            ->assertSee('Tanggal & waktu', false)
            ->assertSee('col-lg-5', false)
            ->assertSee('col-lg-2', false)
            ->assertSee('Perbandingan')
            ->assertSee('31,2°C')
            ->assertSee('68,5%')
            ->assertSee('hujan ringan')
            ->assertSee('Kandang lebih panas 2,3°C')
            ->assertSee('Kandang lebih lembap 1,5%');

        $this->get('/')->assertOk();

        $this->assertDatabaseCount('outdoors', 1);
        $this->assertDatabaseHas('outdoors', [
            'suhu' => 31.2,
            'kelembapan' => 68.5,
            'status_cuaca' => 'hujan ringan',
        ]);

        Http::assertSentCount(1);
        Http::assertSent(function (Request $request): bool {
            $query = [];
            parse_str(parse_url($request->url(), PHP_URL_QUERY) ?? '', $query);

            return parse_url($request->url(), PHP_URL_PATH) === '/data/4.0/onecall/timeline/15min'
                && $query['lat'] === '-6.2'
                && $query['lon'] === '106.8'
                && $query['units'] === 'metric'
                && $query['lang'] === 'id';
        });
    }

    public function test_dashboard_keeps_working_and_shows_warning_when_openweather_fails(): void
    {
        config([
            'services.openweather.key' => 'test-key',
            'services.openweather.lat' => '-6.2',
            'services.openweather.lon' => '106.8',
        ]);

        Http::fake([
            'https://api.openweathermap.org/data/4.0/onecall/timeline/15min*' => Http::response([
                'message' => 'Invalid API key',
            ], 401),
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Data Cuaca')
            ->assertSee('OpenWeather menolak API key.');

        $this->assertDatabaseCount('outdoors', 0);
    }

    public function test_dashboard_compares_when_outdoor_is_hotter_and_more_humid(): void
    {
        config([
            'services.openweather.key' => 'test-key',
            'services.openweather.lat' => '-6.2',
            'services.openweather.lon' => '106.8',
        ]);

        Sensor::query()->create([
            'temperature' => 30,
            'humidity' => 60,
            'thi' => 28,
            'status' => 'Normal',
        ]);

        Http::fake([
            'https://api.openweathermap.org/data/4.0/onecall/timeline/15min*' => Http::response([
                'data' => [[
                    'dt' => now()->timestamp,
                    'temp' => 32,
                    'humidity' => 70,
                    'weather' => [[
                        'main' => 'Clouds',
                        'description' => 'berawan',
                    ]],
                ]],
            ]),
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Luar lebih panas 2,0°C')
            ->assertSee('Luar lebih lembap 10,0%');
    }
}
