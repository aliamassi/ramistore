<?php

use App\Http\Controllers\Front\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\MenuController;

Route::get('/store-data',function(){

    $categories = [
        'hot-drinks'=>[
            'products'=>[
                ['id' => 'hd-1', 'name' => 'Espresso', 'price' => 2.50, 'image' => '/images/products/espresso.jpeg'],
                ['id' => 'hd-2', 'name' => 'Cappuccino', 'price' => 3.25, 'image' => '/images/products/cappuccino.jpeg'],
                ['id' => 'hd-3', 'name' => 'Latte', 'price' => 3.50, 'image' => '/images/products/latte.jpg'],
                ['id' => 'hd-4', 'name' => 'Tea', 'price' => 1.80, 'image' => '/images/products/tea.jpeg'],
            ]
        ],
        'waffles'=>[
            'products'=>[
                ['id' => 'wf-1', 'name' => 'Classic Waffle', 'price' => 4.50, 'image' => '/images/products/waffle-classic.jpeg'],
                ['id' => 'wf-2', 'name' => 'Chocolate Chip', 'price' => 4.90, 'image' => '/images/products/waffle-choco.jpeg'],
                ['id' => 'wf-3', 'name' => 'Strawberry', 'price' => 5.20, 'image' => '/images/products/waffle-strawberry.jpeg'],
            ],
        ],
        'ice-cream'=>[
            'products'=>[
                ['id' => 'ic-1', 'name' => 'Vanilla Scoop', 'price' => 2.20, 'image' => '/images/products/vanilla-ice-cream.jpeg'],
                ['id' => 'ic-2', 'name' => 'Chocolate Scoop', 'price' => 2.20, 'image' => '/images/products/chocolate-ice-cream.jpeg'],
                ['id' => 'ic-3', 'name' => 'Mint Scoop', 'price' => 2.40, 'image' => '/images/products/mint-ice-cream.jpeg'],
            ]
        ],
        'juices'=>[
            'products'=>[
                ['id' => 'js-1', 'name' => 'Orange Juice', 'price' => 2.80, 'image' => '/images/products/orange.jpeg'],
                ['id' => 'js-2', 'name' => 'Apple Juice', 'price' => 2.60, 'image' => '/images/products/apple.jpeg'],
                ['id' => 'js-3', 'name' => 'Mango Juice', 'price' => 3.00, 'image' => '/images/products/mango.jpeg'],
            ]
        ]
    ];
    foreach ($categories as $key => $category){
        $products = $category['products'];
        $cat = \App\Models\Category::create(['name'=>$key]);
        foreach ($products as $product){
            $cat->products()->create($product);
        }
    }

});
Route::get('/', [HomeController::class, 'index'])->name('home');
//Route::get('/', fn () => redirect()->route('menu.index'));
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');

Route::post('/cart/add', [MenuController::class, 'add'])->name('cart.add');
Route::post('/cart/remove', [MenuController::class, 'remove'])->name('cart.remove');
Route::post('/cart/remove-line', [MenuController::class, 'removeLine'])->name('cart.removeLine');
Route::post('/cart/clear', [MenuController::class, 'clear'])->name('cart.clear');

// WhatsApp sharing routes
Route::get('/share-whatsapp', [MenuController::class, 'shareWithProductImages'])->name('whatsapp.share');
Route::get('/share-whatsapp-all', [MenuController::class, 'shareWithAllImages'])->name('whatsapp.share.all');
