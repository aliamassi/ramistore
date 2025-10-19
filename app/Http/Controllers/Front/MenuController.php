<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    // Your existing catalog array...

    public function index(Request $request)
    {

        $cart = $this->getCart();
        $total = $this->cartTotal($cart);
        $user = null;
        $waLink = $this->buildWhatsappLink($cart, $total);
        if($request->id){
            $categories = Category::where('admin_id',$request->id)->with('products')->get();
            $user = Admin::find($request->id);
        }else{
            $categories = Category::with('products')->get();
        }
        $setting = Setting::all()->keyBy('key');

        return view('menu', [
            'categories' => $categories,
            'cart' => $cart,
            'cartTotal' => $total,
            'waLink' => $waLink,
            'user' => $user,
            'setting' => $setting,
        ]);
    }

    /**
     * Share cart with individual product images
     * This method creates multiple WhatsApp links for sharing images
     */
    public function shareWithProductImages(Request $request)
    {
        $cart = $this->getCart();
        $phone = env('WHATSAPP_PHONE');

        if (empty($cart)) {
            return redirect()->route('menu.index')->with('error', 'Cart is empty');
        }

        if (!$phone) {
            return redirect()->route('menu.index')->with('error', 'WhatsApp not configured');
        }

        $cleanPhone = $this->cleanPhoneNumber($phone);

        if (!$cleanPhone) {
            return redirect()->route('menu.index')->with('error', 'Invalid WhatsApp phone number');
        }

        // Create multiple WhatsApp links
        $links = [];

        // 1. First link: Send the order text
        $orderText = $this->buildOrderText($cart, $this->cartTotal($cart));
        $links[] = [
            'url' => "https://wa.me/{$cleanPhone}?text=" . rawurlencode($orderText),
            'type' => 'text',
            'title' => 'Send Order Details',
            'description' => 'Send your complete order details first'
        ];

        // 2. Links for each product image
        foreach ($cart as $item) {
            $imageUrl = url($item['image']);
            $imageText = "Here's the {$item['name']} from my order:\n\n";
            $imageText .= "Quantity: {$item['qty']}\n";
            $imageText .= "Price: $" . number_format($item['price'], 2) . " each\n";
            $imageText .= "Subtotal: $" . number_format($item['price'] * $item['qty'], 2) . "\n\n";
            $imageText .= "Image: {$imageUrl}";

            $links[] = [
                'url' => "https://wa.me/{$cleanPhone}?text=" . rawurlencode($imageText),
                'type' => 'image',
                'title' => "Share {$item['name']}",
                'description' => "Share image and details for {$item['name']}",
                'image' => $item['image'],
                'product' => $item
            ];
        }

        return view('whatsapp-share', compact('links', 'cart'));
    }

    /**
     * Generate a single WhatsApp link with all product images referenced
     */
    public function shareWithAllImages(Request $request)
    {
        $cart = $this->getCart();
        $phone = env('WHATSAPP_PHONE');

        if (empty($cart) || !$phone) {
            return back()->with('error', 'Cart is empty or WhatsApp not configured');
        }

        $cleanPhone = $this->cleanPhoneNumber($phone);
        if (!$cleanPhone) {
            return back()->with('error', 'Invalid WhatsApp phone number');
        }

        $message = $this->buildOrderText($cart, $this->cartTotal($cart));
        $message .= "\n\n📸 Product Images:\n";

        foreach ($cart as $item) {
            $message .= "\n• {$item['name']}: " . url($item['image']);
        }

        $whatsappUrl = "https://wa.me/{$cleanPhone}?text=" . rawurlencode($message);

        return redirect($whatsappUrl);
    }

    /**
     * Build order text message
     */
    private function buildOrderText(array $cart, float $total): string
    {
        $lines = [];
        $lines[] = "Hello! I'd like to order from Milano Flower:";
        $lines[] = "";

        foreach ($cart as $line) {
            $lineTotal = number_format($line['price'] * $line['qty'], 2);
            $lines[] = "• {$line['name']} x {$line['qty']} = \${$lineTotal}";
        }

        $lines[] = "";
        $lines[] = "📊 *Total: $" . number_format($total, 2) . "*";
        $lines[] = "";
        $lines[] = "Thank you! 🙏";

        return implode("\n", $lines);
    }

    /**
     * Enhanced WhatsApp link builder
     */
    private function buildWhatsappLink(array $cart, float $total): string
    {
        $phone = env('WHATSAPP_PHONE');

        $text = $this->buildOrderText($cart, $total);

        if ($phone) {
            $cleanPhone = $this->cleanPhoneNumber($phone);

            if ($cleanPhone) {
                return "https://wa.me/{$cleanPhone}?text=" . rawurlencode($text);
            }

            \Log::warning("Invalid WhatsApp phone format: {$phone}");
        }

        return $this->getFallbackWhatsappLink($text);
    }

    /**
     * Clean and validate phone number
     */
    private function cleanPhoneNumber(string $phone): ?string
    {
        // Clean phone number: remove spaces, dashes, plus signs, parentheses
        $cleanPhone = preg_replace('/[\s\-\+\(\)]/', '', $phone);

        // If phone starts with "00", convert to proper international format
        if (str_starts_with($cleanPhone, '00')) {
            $cleanPhone = substr($cleanPhone, 2);
        }

        // Validate phone number format (digits only, 10–15 length)
        if (preg_match('/^\d{10,15}$/', $cleanPhone)) {
            return $cleanPhone;
        }

        return null;
    }

    private function getFallbackWhatsappLink(string $text): string
    {
        return "https://api.whatsapp.com/send?text=" . rawurlencode($text);
    }

    // Your existing methods remain the same...
    public function add(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|string',
        ]);


        $product =  Product::find($data['id']);
        if (!$product) {
            return back()->with('error', 'Product not found.');
        }

        $cart = $this->getCart();

        if (!isset($cart[$product['id']])) {
            $cart[$product['id']] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'image' =>  isset($product->getMedia('products')[0])?$product->getMedia('products')[0]->getFullUrl():'',
                'qty' => 0,
            ];
        }
        $cart[$product['id']]['qty']++;
        session(['cart' => $cart]);

        $cart = $this->getCart();
        $total = $this->cartTotal($cart);

        $waLink = $this->buildWhatsappLink($cart, $total);

        $categories = Category::with('products')->get();
        session()->put('showCartBar', true);
        session()->put('total', "$$total");
        session()->put('count', array_sum(array_map(fn($i) => $i['qty'], $cart)));
        $cart_view = view('partial.cart', [
            'success' => $product['name'] . ' added to cart.',
            'showCartBar' => true,
            'total' => "$$total",
            'cartTotal' => "$total",
            'waLink' => "$waLink",
            'cart' => $cart,
            'categories' => $categories,
            'count' => array_sum(array_map(fn($i) => $i['qty'], $cart)),
        ])->render();
        $mobile_cart_view = view('partial.mobile_cart', [
            'success' => $product['name'] . ' added to cart.',
            'showCartBar' => true,
            'total' => "$$total",
            'count' => array_sum(array_map(fn($i) => $i['qty'], $cart)),
        ])->render();
        return response()->json([
            'status' => true,
            'cart_view' => $cart_view,
            'mobile_cart_view' => $mobile_cart_view,

        ]);
//        return back()->with([
//            'success' => $product['name'] . ' added to cart.',
//            'showCartBar' => true,
//            'total' => "$$total",
//            'count' => array_sum(array_map(fn($i) => $i['qty'], $cart)),
//        ]);
    }

    public function remove(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|string',
        ]);

        $cart = $this->getCart();
        if (isset($cart[$data['id']])) {
            $cart[$data['id']]['qty']--;
            if ($cart[$data['id']]['qty'] <= 0) {
                unset($cart[$data['id']]);
            }
            session(['cart' => $cart]);
        }

        return response()->json([
            'status' => true,
            'qty' => $cart[$data['id']]['qty'] ?? 0,
            'message' => 'Successfully removed'
        ]);
    }

    public function removeLine(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|string',
        ]);

        $cart = $this->getCart();
        if (isset($cart[$data['id']])) {
            unset($cart[$data['id']]);
            session(['cart' => $cart]);
        }

        $cart = $this->getCart();
        $total = $this->cartTotal($cart);
        session()->put('total', "$$total");
        session()->put('count', array_sum(array_map(fn($i) => $i['qty'], $cart)));

        $mobile_cart_view = view('partial.mobile_cart', [
            'success' => 'Item removed from cart.',
            'showCartBar' => true,
            'total' => "$$total",
            'count' => array_sum(array_map(fn($i) => $i['qty'], $cart)),
        ])->render();

        return response()->json([
            'status' => true,
            'mobile_cart_view' => $mobile_cart_view,
            'message' => 'Successfully removed'
        ]);
    }

    public function clear()
    {
        session()->forget('cart');
        return back();
    }

    // Helper methods
    private function getCart(): array
    {
        return session('cart', []);
    }

    private function cartTotal(array $cart): float
    {
        return array_reduce($cart, fn($c, $item) => $c + ($item['price'] * $item['qty']), 0.0);
    }

    private function findProductById($id)
    {
        $product = Product::find($id);
        if ($product) return $id;
        return null;
    }
}
