<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ImportExportController extends Controller
{
    public function importForm()
    {
        return view('admin.import-export.form');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');

        $successCount = 0;
        $errorCount = 0;

        if ($handle !== false) {
            $headers = fgetcsv($handle);
            $row = 1;

            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $row++;

                if (count($data) < count($headers)) {
                    $errorCount++;
                    continue;
                }

                $productData = array_combine($headers, $data);

                if (empty($productData['name']) || empty($productData['price'])) {
                    $errorCount++;
                    continue;
                }

                try {
                    Product::create([
                        'name' => $productData['name'] ?? '',
                        'details' => $productData['details'] ?? '',
                        'price' => $productData['price'] ?? 0,
                        'status' => $productData['status'] ?? 'active',
                        'image' => $productData['image'] ?? null,
                        'sizes' => isset($productData['sizes']) ? explode('|', $productData['sizes']) : [],
                        'colors' => isset($productData['colors']) ? explode('|', $productData['colors']) : [],
                        'categories' => isset($productData['categories']) ? explode('|', $productData['categories']) : [],
                    ]);

                    $successCount++;
                } catch (\Exception $e) {
                    $errorCount++;
                }
            }

            fclose($handle);
        }

        $message = "Import completed. Success: {$successCount}, Errors: {$errorCount}";

        return back()->with('success', $message);
    }

    public function export()
    {
        $products = Product::all(['name', 'details', 'price', 'status', 'sizes', 'colors', 'categories', 'image']);

        $filename = 'products_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($products) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['name', 'details', 'price', 'status', 'sizes', 'colors', 'categories', 'image']);

            foreach ($products as $product) {
                fputcsv($handle, [
                    $product->name,
                    $product->details,
                    $product->price,
                    $product->status,
                    implode('|', $product->sizes ?? []),
                    implode('|', $product->colors ?? []),
                    implode('|', $product->categories ?? []),
                    $product->image,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
