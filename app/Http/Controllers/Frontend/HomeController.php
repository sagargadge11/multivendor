<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;
class HomeController extends Controller {
    public function index(): View {
        $products = Product::published()->with(['images','vendor'])->latest()->limit(12)->get();
        $categories = Category::where('status',true)->whereNull('parent_id')->latest()->limit(8)->get();
        return view('frontend.home', compact('products','categories'));
    }
}
