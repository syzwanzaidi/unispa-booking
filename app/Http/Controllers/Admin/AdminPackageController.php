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
        $request->validate([
            'package_name' => 'required|string|max:255',
            'package_desc' => 'nullable|string|max:1000',
            'package_price' => 'required|numeric|min:0.01',
            'duration' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1',
        ]);

        try {
            $originalPackageInDb = Package::find($package->package_id);

            Log::info("--- Package Update Request Started ---");
            Log::info("Package ID being edited: " . $package->package_id);
            Log::info("Original Package State (from DB for comparison): " . json_encode($originalPackageInDb->toArray()));
            Log::info("Incoming Request Data: " . json_encode($request->all()));

            $originalName = $originalPackageInDb->package_name;
            $originalDescription = $originalPackageInDb->package_desc;
            $originalCapacity = (int)$originalPackageInDb->capacity;

            $newName = $request->input('package_name');
            $newDescription = $request->input('package_desc');
            $newCapacity = (int)$request->input('capacity');

            $groupingAttributesChanged = false;

            if ($newName !== $originalName) {
                $groupingAttributesChanged = true;
                Log::info("Grouping attribute 'package_name' changed. Old: '{$originalName}', New: '{$newName}'");
            }
            if ($newDescription !== $originalDescription) {
                $groupingAttributesChanged = true;
                Log::info("Grouping attribute 'package_desc' changed. Old: '{$originalDescription}', New: '{$newDescription}'");
            }
            if ($newCapacity !== $originalCapacity) {
                $groupingAttributesChanged = true;
                Log::info("Grouping attribute 'capacity' changed. Old: '{$originalCapacity}', New: '{$newCapacity}'");
            }
            
            Log::info("Grouping attributes changed flag: " . ($groupingAttributesChanged ? 'TRUE' : 'FALSE'));

            if ($groupingAttributesChanged) {
                Log::info("Executing grouped update logic.");
                
                $updatedCount = Package::where('package_name', $originalName)
                       ->where('package_desc', $originalDescription)
                       // ->where('capacity', $originalCapacity) 
                       ->update([
                           'package_name' => $newName,
                           'package_desc' => $newDescription,
                           'capacity' => $newCapacity,
                       ]);
                Log::info("Mass update affected {$updatedCount} packages.");

                $package->fill($request->all());
                $package->save();
                Log::info("Specific package (ID: {$package->package_id}) updated with all request data.");

            } else {
                Log::info("Executing individual update logic (grouping attributes unchanged).");
                $package->update($request->all());
                Log::info("Specific package (ID: {$package->package_id}) updated individually.");
            }

            Log::info("--- Package Update Request Finished Successfully ---");
            return redirect()->route('admin.packages.index')->with('success', 'Package updated successfully!');

        } catch (\Exception $e) {
            Log::error("Failed to update package (ID: {$package->package_id}): " . $e->getMessage());
            Log::error("Error trace: " . $e->getTraceAsString());
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