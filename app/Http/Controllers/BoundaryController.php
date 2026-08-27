<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BoundaryService;

class BoundaryController extends Controller
{
    public function getBoundary(Request $request, BoundaryService $boundaryService)
    {
        $province = $request->query('province');
        $district = $request->query('district');
        $ward = $request->query('ward');

        if (!$province || !$district || !$ward) {
            return response()->json([
                'success' => false,
                'message' => 'Missing location parameters.'
            ], 400);
        }

        // Standardize strings, sometimes UI prefixes with "Tỉnh", "Thành phố"
        $provinceStr = preg_replace('/^(Tỉnh|Thành phố)\s+/iu', '', $province);
        $districtStr = preg_replace('/^(Huyện|Quận|Thị xã|Thành phố)\s+/iu', '', $district);
        $wardStr = preg_replace('/^(Xã|Phường|Thị trấn)\s+/iu', '', $ward);

        $result = $boundaryService->getWardBoundary($provinceStr, $districtStr, $wardStr);

        return response()->json($result);
    }
}
