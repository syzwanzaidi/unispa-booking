<?php

namespace App\Http\Controllers;

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

        return view('packages.index', [
            'categorizedPackages' => $categorizedAndGroupedPackages,
        ]);
    }

    public function create()
    {
        return view('packages.create'); // Show form to add new package
    }
    public function store(Request $request)
    {
        $request->validate([
            'package_name' => 'required|string|max:255',
            'package_desc' => 'nullable|string',
            'package_price' => 'required|numeric|min:0',
            'duration' => 'required|string|max:255',
        ]);
        Package::create([
            'package_name' => $request->package_name,
            'package_desc' => $request->package_desc,
            'package_price' => $request->package_price,
            'duration' => $request->duration,
        ]);
        return redirect()->route('packages.index')->with('success', 'Package created successfully!');
    }
    public function show(Package $package)
    {
        return view('packages.show', compact('package'));
    }
    public function edit(Package $package)
    {
        return view('packages.edit', compact('package'));
    }
    public function update(Request $request, Package $package)
    {
        $request->validate([
            'package_name' => 'required|string|max:255',
            'package_desc' => 'nullable|string',
            'package_price' => 'required|numeric|min:0',
            'duration' => 'required|string|max:255',
        ]);
        $package->update([
            'package_name' => $request->package_name,
            'package_desc' => $request->package_desc,
            'package_price' => $request->package_price,
            'duration' => $request->duration,
        ]);
        return redirect()->route('packages.index')->with('success', 'Package updated successfully!');
    }
    public function destroy(Package $package)
    {
        $package->delete(); // Delete the package
        return redirect()->route('packages.index')->with('success', 'Package deleted successfully!');
    }

}
