<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminPackageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index()
    {
        $packages = Package::orderBy('package_id')->get();
        return view('admin.packages.index', compact('packages'));
    }
    public function create()
    {
        return view('admin.packages.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'package_name' => 'required|string|max:255|unique:packages,package_name',
            'package_desc' => 'nullable|string|max:1000',
            'package_price' => 'required|numeric|min:0.01',
            'duration' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1',
        ]);

        try {
            // Create a new Package record
            Package::create($request->all());
            return redirect()->route('admin.packages.index')->with('success', 'Package added successfully!');
        } catch (\Exception $e) {
            Log::error("Failed to add package: " . $e->getMessage());
            return back()->with('error', 'Failed to add package. Please try again.')->withInput();
        }
    }
    public function edit(Package $package)
    {
        return view('admin.packages.edit', compact('package'));
    }
    public function update(Request $request, Package $package)
    {
        // Validate the incoming request data for package update
        $request->validate([
            'package_name' => 'required|string|max:255|unique:packages,package_name,' . $package->package_id . ',package_id', // Unique except for itself
            'package_desc' => 'nullable|string|max:1000',
            'package_price' => 'required|numeric|min:0.01',
            'duration' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1',
        ]);

        try {
            // Update the Package record
            $package->update($request->all());
            return redirect()->route('admin.packages.index')->with('success', 'Package updated successfully!');
        } catch (\Exception $e) {
            Log::error("Failed to update package (ID: {$package->package_id}): " . $e->getMessage());
            return back()->with('error', 'Failed to update package. Please try again.')->withInput();
        }
    }

    public function destroy(Package $package)
    {
        try {
            $package->delete();
            return redirect()->route('admin.packages.index')->with('success', 'Package deleted successfully!');
        } catch (\Exception $e) {
            Log::error("Failed to delete package (ID: {$package->package_id}): " . $e->getMessage());
            return back()->with('error', 'Failed to delete package. Please try again.');
        }
    }
}
