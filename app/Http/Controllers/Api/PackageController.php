<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $allPackages = Package::all();

        $categorizedAndGroupedPackages = $allPackages
            ->groupBy('category')
            ->map(function ($categoryPackages) {
                return $categoryPackages->groupBy('package_name')->map(function ($namedPackages) {
                    $mainPackageInfo = $namedPackages->first();
                    $options = $namedPackages->map(function ($package) {
                        return (object) [
                            'package_id' => $package->package_id,
                            'duration' => $package->duration,
                            'package_price' => $package->package_price,
                            'capacity' => $package->capacity,
                        ];
                    })
                    ->sortBy(function($option) {
                        if (str_contains($option->duration, 'Minutes')) {
                            return (int) str_replace(' Minutes', '', $option->duration);
                        }
                        if (str_contains($option->duration, 'N/A')) {
                            return 9999;
                        }
                        return 0;
                    })
                    ->values();

                    return (object) [
                        'package_name' => $mainPackageInfo->package_name,
                        'package_desc' => $mainPackageInfo->package_desc,
                        'options' => $options,
                    ];
                });
            });

        return response()->json(['categorizedPackages' => $categorizedAndGroupedPackages]);
    }

    public function show(Package $package)
    {
        return response()->json(['package' => $package]);
    }
}
