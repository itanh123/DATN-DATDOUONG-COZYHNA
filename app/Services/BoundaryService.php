<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class BoundaryService
{
    /**
     * Get GeoJSON boundary for a specific Ward (Xã/Phường) in Vietnam.
     * Caches the result to avoid rate limits from Nominatim.
     */
    public function getWardBoundary($province, $district, $ward)
    {
        // Build a unique cache key based on the location names
        $cacheKey = 'boundary_' . md5($province . '_' . $district . '_' . $ward);

        return Cache::remember($cacheKey, now()->addDays(30), function () use ($province, $district, $ward) {
            try {
                // Try full query first
                $queries = [
                    "{$ward}, {$district}, {$province}, Vietnam",
                    "{$ward}, {$province}, Vietnam" // Fallback if district link is missing
                ];

                foreach ($queries as $query) {
                    $response = Http::withHeaders([
                        'User-Agent' => 'CozyHna/1.0 (contact@cozyhna.com)'
                    ])->get('https://nominatim.openstreetmap.org/search', [
                        'q' => $query,
                        'format' => 'json',
                        'polygon_geojson' => 1,
                        'limit' => 1,
                        'extratags' => 1
                    ]);

                    if ($response->successful()) {
                        $data = $response->json();
                        
                        if (!empty($data)) {
                            if (isset($data[0]['geojson'])) {
                                $geojson = $data[0]['geojson'];
                                if ($geojson['type'] === 'Polygon' || $geojson['type'] === 'MultiPolygon') {
                                    return [
                                        'success' => true,
                                        'geojson' => $geojson,
                                        'display_name' => $data[0]['display_name'],
                                        'lat' => $data[0]['lat'],
                                        'lon' => $data[0]['lon'],
                                    ];
                                }
                            }
                            // Fallback if we found the place but no polygon
                            return [
                                'success' => false,
                                'lat' => $data[0]['lat'],
                                'lon' => $data[0]['lon'],
                                'message' => 'Không có polygon ranh giới, chỉ có tọa độ điểm.'
                            ];
                        }
                    }
                }
                
                return [
                    'success' => false,
                    'message' => 'Không tìm thấy ranh giới cho địa điểm này.'
                ];
            } catch (\Exception $e) {
                Log::error('BoundaryService Error: ' . $e->getMessage());
                return [
                    'success' => false,
                    'message' => 'Lỗi kết nối đến dịch vụ bản đồ.'
                ];
            }
        });
    }
}
