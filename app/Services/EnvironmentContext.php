<?php

namespace App\Services;

use App\Models\Outdoor;
use App\Models\Sensor;

class EnvironmentContext
{
    public function forSensor(?Sensor $sensor): string
    {
        $outdoor = Outdoor::query()->latest('created_at')->first();

        $sensorContext = $sensor
            ? sprintf(
                'Data sensor kandang terakhir: suhu %s°C, kelembapan %s%%, THI %s, status %s.',
                $this->format((float) $sensor->temperature),
                $this->format((float) $sensor->humidity),
                $sensor->thi === null ? 'tidak tersedia' : $this->format((float) $sensor->thi, 2),
                $sensor->status
            )
            : 'Belum ada data sensor kandang.';

        $outdoorContext = $outdoor
            ? sprintf(
                'Data OpenWeather tersimpan terakhir: suhu luar %s°C, kelembapan luar %s%%, kondisi %s (diperbarui %s).',
                $this->format($outdoor->suhu),
                $this->format($outdoor->kelembapan),
                $outdoor->status_cuaca,
                $outdoor->created_at?->diffForHumans() ?? 'waktu tidak tersedia'
            )
            : 'Data OpenWeather belum tersedia.';

        if (! $sensor || ! $outdoor) {
            $comparisonContext = 'Perbandingan sensor kandang dengan OpenWeather belum tersedia.';
        } else {
            $temperatureDifference = round((float) $sensor->temperature - $outdoor->suhu, 1);
            $humidityDifference = round((float) $sensor->humidity - $outdoor->kelembapan, 1);

            $comparisonContext = sprintf(
                'Perbandingan suhu: %s. Perbandingan kelembapan: %s.',
                $this->comparison($temperatureDifference, 'Kandang lebih panas', 'Luar lebih panas', 'Suhu sama', '°C'),
                $this->comparison($humidityDifference, 'Kandang lebih lembap', 'Luar lebih lembap', 'Kelembapan sama', '%')
            );
        }

        return implode("\n", [$sensorContext, $outdoorContext, $comparisonContext]);
    }

    private function comparison(
        float $difference,
        string $indoorHigher,
        string $outdoorHigher,
        string $equal,
        string $unit
    ): string {
        if ($difference > 0) {
            return $indoorHigher.' '.$this->format($difference).$unit;
        }

        if ($difference < 0) {
            return $outdoorHigher.' '.$this->format(abs($difference)).$unit;
        }

        return $equal;
    }

    private function format(float $value, int $decimals = 1): string
    {
        return number_format($value, $decimals, ',', '.');
    }
}
