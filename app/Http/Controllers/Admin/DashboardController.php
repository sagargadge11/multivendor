<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Enquiry;
use App\Models\User;
use Illuminate\View\View;
class DashboardController extends Controller {
    public function index(): View {
        $stats = [
            'vendors' => Vendor::count(),
            'pending_vendors' => Vendor::where('verification_status','pending')->count(),
            'products' => Product::count(),
            'pending_products' => Product::where('approval_status','pending')->count(),
            'enquiries' => Enquiry::count(),
            'customers' => User::where('role','customer')->count(),
        ];
        return view('admin.dashboard', compact('stats'));
    }
}
