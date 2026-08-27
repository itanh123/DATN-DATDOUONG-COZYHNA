<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Setting;
use Exception;
use Illuminate\Support\Facades\Log;

class DeliveryService
{
    /**
     * Parse the shipping_tiers from JSON or use defaults.
     * Tier format: [{"max_km": 3, "fee": 15000}, {"max_km": 5, "fee": 20000}, ...]
     */
    public function getShippingTiers()
    {
        $tiersJson = Setting::get('shipping_tiers');
        
        if (empty($tiersJson)) {
            // Default tiers if nothing is configured
            return [
                ['max_km' => 3, 'fee' => 15000],
                ['max_km' => 5, 'fee' => 20000],
                ['max_km' => 10, 'fee' => 30000],
                ['max_km' => 15, 'fee' => 40000],
            ];
        }

        try {
            $tiers = json_decode($tiersJson, true);
            // Sort by max_km to evaluate sequentially
            usort($tiers, function($a, $b) {
                return $a['max_km'] <=> $b['max_km'];
            });
            return $tiers;
        } catch (Exception $e) {
            Log::error('Lỗi khi phân tích shipping_tiers: ' . $e->getMessage());
            return [
                ['max_km' => 3, 'fee' => 15000],
                ['max_km' => 10, 'fee' => 30000],
            ];
        }
    }

    /**
     * Calculate route distance and duration between two coordinates using OSRM
     * Returns array: ['distance_km' => float, 'duration_minutes' => int, 'method' => 'routing'|'straight_line']
     */
    public function calculateRoute($fromLon, $fromLat, $toLon, $toLat)
    {
        try {
            $url = "https://router.project-osrm.org/route/v1/driving/{$fromLon},{$fromLat};{$toLon},{$toLat}?overview=false";
            
            $response = Http::timeout(5)->get($url);
            
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['code']) && $data['code'] === 'Ok' && !empty($data['routes'])) {
                    $route = $data['routes'][0];
                    return [
                        'distance_km' => round($route['distance'] / 1000, 1),
                        'duration_minutes' => round($route['duration'] / 60),
                        'method' => 'routing'
                    ];
                }
            }
        } catch (Exception $e) {
            Log::error('Lỗi tính khoảng cách OSRM: ' . $e->getMessage());
        }

        // Fallback to straight-line distance (haversine) if routing fails
        $distance = $this->haversineDistance($fromLat, $fromLon, $toLat, $toLon);
        // Estimate duration based on average 30km/h speed in city
        $durationMinutes = round(($distance / 30) * 60);

        return [
            'distance_km' => round($distance, 1),
            'duration_minutes' => $durationMinutes,
            'method' => 'straight_line'
        ];
    }

    /**
     * Calculate distance between two coordinates using Haversine formula (fallback)
     */
    private function haversineDistance($lat1, $lon1, $lat2, $lon2) {
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return $earthRadius * $c;
    }

    /**
     * Calculate shipping fee based on distance
     */
    public function calculateShippingFee($distanceKm)
    {
        if ($distanceKm === null || $distanceKm < 0) {
            return (int) Setting::get('base_shipping_fee', 15000); // Fallback base fee
        }

        $tiers = $this->getShippingTiers();
        
        foreach ($tiers as $tier) {
            if ($distanceKm <= $tier['max_km']) {
                return (int) $tier['fee'];
            }
        }
        
        // If greater than the highest tier, charge an extra fee per additional km
        $highestTier = end($tiers);
        $extraKm = $distanceKm - $highestTier['max_km'];
        
        // Example: 5000vnd per extra km over the max tier
        $feePerExtraKm = (float) Setting::get('fee_per_km', 5000); 
        
        return (int) ($highestTier['fee'] + round($extraKm * $feePerExtraKm));
    }
}
