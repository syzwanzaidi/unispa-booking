<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class ApiPackageController extends Controller
{
    // ---Web Content uses index() inside PackageController
    // public function index()
    // {
    //     $allPackages = Package::all();

    //     $categorizedAndGroupedPackages = $allPackages
    //         ->groupBy('category')
    //         ->map(function ($categoryPackages) {
    //             return $categoryPackages->groupBy('package_name')->map(function ($namedPackages) {
    //                 $mainPackageInfo = $namedPackages->first();
    //                 $options = $namedPackages->map(function ($package) {
    //                     return (object) [
    //                         'package_id' => $package->package_id,
    //                         'duration' => $package->duration,
    //                         'package_price' => $package->package_price,
    //                         'capacity' => $package->capacity,
    //                     ];
    //                 })
    //                 ->sortBy(function($option) {
    //                     if (str_contains($option->duration, 'Minutes')) {
    //                         return (int) str_replace(' Minutes', '', $option->duration);
    //                     }
    //                     if (str_contains($option->duration, 'N/A')) {
    //                         return 9999;
    //                     }
    //                     return 0;
    //                 })
    //                 ->values();

    //                 return (object) [
    //                     'package_name' => $mainPackageInfo->package_name,
    //                     'package_desc' => $mainPackageInfo->package_desc,
    //                     'options' => $options,
    //                 ];
    //             });
    //         });

    //     return response()->json(['categorizedPackages' => $categorizedAndGroupedPackages]);
    // }

    public function index()
    {
        $allPackages = Package::all();

        $categorizedAndGroupedPackages = $allPackages
            ->groupBy('category')
            ->map(function ($categoryPackages) {
                return $categoryPackages->groupBy('package_name')->map(function ($namedPackages) {
                    $mainPackageInfo = $namedPackages->first();
                    $options = $namedPackages->map(function ($package) {
                        return [
                            'package_id' => $package->package_id,
                            'duration' => $package->duration,
                            'package_price' => $package->package_price,
                            'capacity' => $package->capacity,
                        ];
                    })
                        ->sortBy(function ($option) {
                            if (str_contains($option['duration'], 'Minutes')) {
                                return (int) str_replace(' Minutes', '', $option['duration']);
                            }
                            if (str_contains($option['duration'], 'N/A')) {
                                return 9999;
                            }
                            return 0;
                        })
                        ->values();

                    return [
                        'package_name' => $mainPackageInfo->package_name,
                        'package_desc' => $mainPackageInfo->package_desc,
                        'options' => $options,
                    ];
                })->values();
            });

        return response()->json([
            'success' => true,
            'packages' => $categorizedAndGroupedPackages,
        ]);
    }

    public function show(Package $package)
    {
        return response()->json(['package' => $package]);
    }

    public function destroy($id)
    {
        $package = Package::findOrFail($id);
        $package->delete();

        return response()->json(['message' => 'Package deleted successfully']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'package_name' => 'required|string',
            'description' => 'required|string',
            'options' => 'required|array',
        ]);

        $package = Package::create([
            'package_name' => $request->package_name,
            'package_desc' => $request->description,
        ]);

        foreach ($request->options as $option) {
            $package->options()->create($option);
        }

        return response()->json(['message' => 'Package created successfully'], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'package_name' => 'required|string',
            'description' => 'required|string',
            'options' => 'required|array',
        ]);

        $package = Package::findOrFail($id);
        $package->update([
            'package_name' => $request->package_name,
            'package_desc' => $request->description,
        ]);

        // Optional: handle options updates — depends on your structure!
        $package->options()->delete();
        foreach ($request->options as $option) {
            $package->options()->create($option);
        }

        return response()->json(['message' => 'Package updated successfully'], 200);
    }
}
