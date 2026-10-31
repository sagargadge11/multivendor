<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateVendorProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $vendor = auth()->user()->vendor;

        abort_unless($vendor, 404, 'Vendor profile not found.');

        return view('vendor.profile.edit', compact('vendor'));
    }

    public function update(UpdateVendorProfileRequest $request): RedirectResponse
    {
        $vendor = $request->user()->vendor;

        abort_unless($vendor, 404, 'Vendor profile not found.');

        $data = $request->validated();
        unset($data['logo'], $data['banner']);

        $oldFiles = [];
        $newFiles = [];

        try {
            if ($request->hasFile('logo')) {
                $newFiles['logo'] = $request->file('logo')
                    ->store('vendors/logos', 'public');
                $oldFiles[] = $vendor->logo;
            }

            if ($request->hasFile('banner')) {
                $newFiles['banner'] = $request->file('banner')
                    ->store('vendors/banners', 'public');
                $oldFiles[] = $vendor->banner;
            }

            $vendor->update(array_merge($data, $newFiles, [
                'profile_completed_at' => now(),
            ]));

            foreach ($oldFiles as $oldFile) {
                if ($oldFile && !in_array($oldFile, $newFiles, true)) {
                    Storage::disk('public')->delete($oldFile);
                }
            }

            return redirect()
                ->route('vendor.profile.edit')
                ->with('success', 'Business profile updated successfully.');
        } catch (\Throwable $exception) {
            foreach ($newFiles as $newFile) {
                Storage::disk('public')->delete($newFile);
            }

            report($exception);

            return back()
                ->withInput()
                ->with('error', 'Unable to update your profile. Please try again.');
        }
    }
}
