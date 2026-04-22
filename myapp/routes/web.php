<?php
use App\Http\Controllers\ReportController;

use Illuminate\Support\Facades\Route;
use App\Models\Product;
Route::get('/', function () {
    return view('welcome');
});


Route::get('/test', function () {
    return 'Laravel route ใช้งานได้';
});

Route::get('/db-test', function () {
    DB::connection()->getPdo();
    return 'เชื่อมต่อฐานข้อมูลสำเร็จ';
});

Route::get('/seed-product', function () {
    Product::create([
        'name' => 'Durian Premium',
        'price' => 250.00,
        'stock' => 10
    ]);

    Product::create([
        'name' => 'Mangosteen A',
        'price' => 120.00,
        'stock' => 25
    ]);

    Product::create([
        'name' => 'Longan Export',
        'price' => 90.00,
        'stock' => 40
    ]);

    return 'เพิ่มข้อมูลสินค้าเรียบร้อย';
});

Route::get('/products', function () {
    $products = Product::all();
    return $products;
});


Route::get('/report/open-pdf', [ReportController::class, 'openPdf'])->name('report.openPdf');