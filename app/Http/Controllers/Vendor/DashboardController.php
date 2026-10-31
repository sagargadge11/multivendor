<?php
namespace App\Http\Controllers\Vendor;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
class DashboardController extends Controller {
    public function index(): View {
        $vendor = auth()->user()->vendor;
        abort_unless($vendor, 404, 'Vendor profile not found.');
        $stats = [
            'products' => $vendor->products()->count(),
            'approved_products' => $vendor->products()->where('approval_status','approved')->count(),
            'pending_products' => $vendor->products()->where('approval_status','pending')->count(),
            'enquiries' => $vendor->enquiries()->count(),
        ];
        return view('vendor.dashboard', compact('vendor','stats'));
    }
}
