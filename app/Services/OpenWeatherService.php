<?php

namespace App\Services;

use App\Exceptions\OpenWeatherException;
use App\Models\Outdoor;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class OpenWeatherService
{
    private const API_URL = 'https://api.openweathermap.org/data/4.0/onecall/timeline/15min';

    private const REFRESH_INTERVAL_MINUTES = 10;

    public function latest(): ?Outdoor
    {
        $latest = Outdoor::query()->latest('created_at')->first();

        if ($latest && $latest->created_at->greaterThan(now()->subMinutes(self::REFRESH_INTERVAL_MINUTES))) {
            return $latest;
        }

        return $this->fetchAndStore();
    }

    public function fetchAndStore(): Outdoor
    {
        $apiKey = config('services.openweather.key');
        $latitude = config('services.openweather.lat');
        $longitude = config('services.openweather.lon');

        if (blank($apiKey) || ! is_numeric($latitude) || ! is_numeric($longitude)) {
            throw new OpenWeatherException('OpenWeather API key and coordinates are not configured.');
        }

        if ((float) $latitude < -90 || (float) $latitude > 90 || (float) $longitude < -180 || (float) $longitude > 180) {
            throw new OpenWeatherException('OpenWeather coordinates are outside the valid range.');
        }

        try {
            $response = Http::acceptJson()
                ->connectTimeout(3)
                ->timeout(10)
                ->get(self::API_URL, [
                    'lat' => $latitude,
                    'lon' => $longitude,
                    'appid' => $apiKey,
                    'units' => 'metric',
                    'lang' => 'id',
                ])
                ->throw();
        } catch (ConnectionException|RequestException $exception) {
            throw new OpenWeatherException('The OpenWeather request failed.', 0, $exception);
        }

        $current = $response->json('data.0');
        $temperature = is_array($current) ? ($current['temp'] ?? null) : null;
        $humidity = is_array($current) ? ($current['humidity'] ?? null) : null;
        $weather = is_array($current) ? ($current['weather'][0] ?? null) : null;
        $condition = is_array($weather) ? ($weather['description'] ?? null) : null;

        if (! is_string($condition) || trim($condition) === '') {
            $condition = is_array($weather) ? ($weather['main'] ?? null) : null;
        }

        if (! is_numeric($temperature) || ! is_finite((float) $temperature)
            || ! is_numeric($humidity) || (float) $humidity < 0 || (float) $humidity > 100
            || ! is_string($condition) || trim($condition) === '') {
            throw new OpenWeatherException('OpenWeather returned an incomplete weather record.');
        }

        return Outdoor::query()->create([
            'suhu' => (float) $temperature,
            'kelembapan' => (float) $humidity,
            'status_cuaca' => trim($condition),
        ]);
    }
}
