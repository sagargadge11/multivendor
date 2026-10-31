<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RejectVendorRequest;
use App\Http\Requests\SuspendVendorRequest;
use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VendorController extends Controller
{
    public function index(Request $request): View
    {
        $query = Vendor::with('user');

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                    ->orWhere('business_email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('verification_status')) {
            $query->where('verification_status', $request->input('verification_status'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('listing_type')) {
            $query->where('listing_type', $request->input('listing_type'));
        }

        $vendors = $query->latest()->paginate(15)->withQueryString();

        $counts = [
            'total' => Vendor::count(),
            'pending' => Vendor::where('verification_status', 'pending')->count(),
            'verified' => Vendor::where('verification_status', 'verified')->count(),
            'rejected' => Vendor::where('verification_status', 'rejected')->count(),
            'suspended' => Vendor::where('status', 'suspended')->count(),
        ];

        return view('admin.vendors.index', compact('vendors', 'counts'));
    }

    public function show(Vendor $vendor): View
    {
        $vendor->load([
            'user',
            'products' => fn ($query) => $query->latest()->limit(10),
            'integrations',
        ]);

        return view('admin.vendors.show', compact('vendor'));
    }

    public function approve(Vendor $vendor): RedirectResponse
    {
        if ($vendor->status === 'suspended') {
            return back()->with('error', 'Reactivate this vendor before approving the account.');
        }

        $vendor->update([
            'verification_status' => 'verified',
            'verified_at' => now(),
            'rejection_reason' => null,
        ]);

        return back()->with('success', 'Vendor verified successfully.');
    }

    public function reject(RejectVendorRequest $request, Vendor $vendor): RedirectResponse
    {
        $vendor->update([
            'verification_status' => 'rejected',
            'verified_at' => null,
            'rejection_reason' => $request->validated('reason'),
        ]);

        return back()->with('success', 'Vendor application rejected.');
    }

    public function suspend(SuspendVendorRequest $request, Vendor $vendor): RedirectResponse
    {
        $vendor->update([
            'status' => 'suspended',
            'suspended_at' => now(),
            'suspension_reason' => $request->validated('reason'),
        ]);

        return back()->with('success', 'Vendor suspended successfully.');
    }

    public function reactivate(Vendor $vendor): RedirectResponse
    {
        $vendor->update([
            'status' => 'active',
            'suspended_at' => null,
            'suspension_reason' => null,
        ]);

        return back()->with('success', 'Vendor account reactivated.');
    }
}
