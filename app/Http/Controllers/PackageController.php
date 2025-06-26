<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $categorizedPackages = Package::all()->groupBy('category');

        return view('packages.index', compact('categorizedPackages'));
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
