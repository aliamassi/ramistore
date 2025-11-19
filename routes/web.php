<?php

use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\MenuController;
use App\Models\Admin;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\OldMenuController;

//Route::get('/restaurant/{name}',function(Request $request){
//
//         $name = $request->name;
//         $setting = \App\Models\Setting::where('key','business_name')->where('value',$name)->first();
//         $admin = Admin::find($setting->admin_id);
//         $categories = $admin->categories()->active()->orderBy('order')->with('products.variants')->latest()->get();
//         $restaurant  = $admin->settings->keyBy('key');
//    return view('menu.index1',compact('categories','restaurant'));
//});
//Route::get('/store-data',function(){
//
//    $categories = [
//        'hot-drinks'=>[
//            'products'=>[
//                ['id' => 'hd-1', 'name' => 'Espresso', 'price' => 2.50, 'image' => '/images/products/espresso.jpeg'],
//                ['id' => 'hd-2', 'name' => 'Cappuccino', 'price' => 3.25, 'image' => '/images/products/cappuccino.jpeg'],
//                ['id' => 'hd-3', 'name' => 'Latte', 'price' => 3.50, 'image' => '/images/products/latte.jpg'],
//                ['id' => 'hd-4', 'name' => 'Tea', 'price' => 1.80, 'image' => '/images/products/tea.jpeg'],
//            ]
//        ],
//        'waffles'=>[
//            'products'=>[
//                ['id' => 'wf-1', 'name' => 'Classic Waffle', 'price' => 4.50, 'image' => '/images/products/waffle-classic.jpeg'],
//                ['id' => 'wf-2', 'name' => 'Chocolate Chip', 'price' => 4.90, 'image' => '/images/products/waffle-choco.jpeg'],
//                ['id' => 'wf-3', 'name' => 'Strawberry', 'price' => 5.20, 'image' => '/images/products/waffle-strawberry.jpeg'],
//            ],
//        ],
//        'ice-cream'=>[
//            'products'=>[
//                ['id' => 'ic-1', 'name' => 'Vanilla Scoop', 'price' => 2.20, 'image' => '/images/products/vanilla-ice-cream.jpeg'],
//                ['id' => 'ic-2', 'name' => 'Chocolate Scoop', 'price' => 2.20, 'image' => '/images/products/chocolate-ice-cream.jpeg'],
//                ['id' => 'ic-3', 'name' => 'Mint Scoop', 'price' => 2.40, 'image' => '/images/products/mint-ice-cream.jpeg'],
//            ]
//        ],
//        'juices'=>[
//            'products'=>[
//                ['id' => 'js-1', 'name' => 'Orange Juice', 'price' => 2.80, 'image' => '/images/products/orange.jpeg'],
//                ['id' => 'js-2', 'name' => 'Apple Juice', 'price' => 2.60, 'image' => '/images/products/apple.jpeg'],
//                ['id' => 'js-3', 'name' => 'Mango Juice', 'price' => 3.00, 'image' => '/images/products/mango.jpeg'],
//            ]
//        ]
//    ];
//    foreach ($categories as $key => $category){
//        $products = $category['products'];
//        $cat = \App\Models\Category::create(['name'=>$key]);
//        foreach ($products as $product){
//            $cat->products()->create($product);
//        }
//    }
//
//});
Route::get('/', [HomeController::class, 'index'])->name('home');
// Menu Routes
Route::get('/restaurant/{name}', [MenuController::class, 'index'])->name('menu.index');
Route::get('/menu/{name}/{id}', [MenuController::class, 'show'])->name('menu.show');

// Cart Routes
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/{name}', [CartController::class, 'index'])->name('index');
    Route::post('/{name}/add', [CartController::class, 'add'])->name('add');
    Route::patch('/{id}', [CartController::class, 'update'])->name('update');
    Route::delete('/{id}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/', [CartController::class, 'clear'])->name('clear');
    Route::get('/count', [CartController::class, 'count'])->name('count');
});


//Route::get('/', fn () => redirect()->route('menu.index'));
//Route::get('/menu', [OldMenuController::class, 'index'])->name('menu.index');
//Route::get('/menu1', [OldMenuController::class, 'index1'])->name('menu.index1');
//Route::get('/menu1/{item}', [OldMenuController::class, 'show'])->name('menu.show');
//
//Route::post('/cart/add', [OldMenuController::class, 'add'])->name('cart.add');
//Route::post('/cart/remove', [OldMenuController::class, 'remove'])->name('cart.remove');
//Route::post('/cart/remove-line', [OldMenuController::class, 'removeLine'])->name('cart.removeLine');
//Route::post('/cart/clear', [OldMenuController::class, 'clear'])->name('cart.clear');
//
//// WhatsApp sharing routes
//Route::get('/share-whatsapp', [OldMenuController::class, 'shareWithProductImages'])->name('whatsapp.share');
//Route::get('/share-whatsapp-all', [OldMenuController::class, 'shareWithAllImages'])->name('whatsapp.share.all');
