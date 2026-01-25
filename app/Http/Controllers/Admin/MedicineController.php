<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index()
    {
        $medicines = Medicine::latest()->get();
        return view('admin.medicines.index', compact('medicines'));
    }

    public function create()
    {
        return view('admin.medicines.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|unique:medicines,sku',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'expiry_date' => 'nullable|date',
        ]);

        Medicine::create($request->all());

        return redirect()->route('admin.medicines.index')
            ->with('success', 'Medicine added successfully.');
    }

    public function edit(Medicine $medicine)
    {
        return view('admin.medicines.edit', compact('medicine'));
    }

    public function update(Request $request, Medicine $medicine)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|unique:medicines,sku,' . $medicine->id,
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'expiry_date' => 'nullable|date',
        ]);

        $medicine->update($request->all());

        return redirect()->route('admin.medicines.index')
            ->with('success', 'Medicine updated successfully.');
    }

    public function destroy(Medicine $medicine)
    {
        $medicine->delete();
        return back()->with('success', 'Medicine deleted.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getPathname(), "r");
        
        // Skip header row
        fgetcsv($handle);

        $count = 0;
        
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // Expected Format: Name, Generic Name, SKU, Price, Stock, Unit, Manufacturer
            // Example: Paracetamol, Acetaminophen, SKU123, 5.00, 100, tablet, ABC Pharma
            
            // Basic validation: ensure we have at least name and price
            if (empty($data[0])) continue; 

            Medicine::updateOrCreate(
                ['sku' => $data[2] ?? null], // Check dupe by SKU if present
                [
                    'name' => $data[0],
                    'generic_name' => $data[1] ?? null,
                    'sku' => $data[2] ?? 'MED-'.uniqid(), // Generate SKU if missing
                    'price' => floatval($data[3] ?? 0),
                    'stock_quantity' => intval($data[4] ?? 0),
                    'unit' => $data[5] ?? 'tablet',
                    'manufacturer' => $data[6] ?? null,
                ]
            );
            $count++;
        }

        fclose($handle);

        return back()->with('success', "$count medicines imported successfully!");
    }

    public function export()
    {
        $filename = "healthflow-medicines-" . date('Y-m-d') . ".csv";
        $handle = fopen('php://memory', 'w');
        
        // Header
        fputcsv($handle, ['Name', 'Generic', 'SKU', 'Price', 'Stock', 'Unit', 'Manufacturer']);

        // Data
        Medicine::chunk(100, function($medicines) use ($handle) {
            foreach ($medicines as $med) {
                fputcsv($handle, [
                    $med->name,
                    $med->generic_name,
                    $med->sku,
                    $med->price,
                    $med->stock_quantity,
                    $med->unit,
                    $med->manufacturer
                ]);
            }
        });

        fseek($handle, 0);

        return response()->stream(
            function () use ($handle) {
                fpassthru($handle);
                fclose($handle);
            },
            200,
            [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ]
        );
    }

    public function list()
    {
        return response()->json(Medicine::select('id', 'name', 'price', 'stock_quantity', 'unit')->get());
    }
}
