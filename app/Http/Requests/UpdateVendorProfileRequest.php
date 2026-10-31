<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVendorProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check()
            && auth()->user()->role === 'vendor'
            && auth()->user()->vendor !== null;
    }

    public function rules(): array
    {
        return [
            'business_name' => ['required', 'string', 'max:255'],
            'business_type' => ['nullable', 'string', 'max:150'],
            'business_email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'website_url' => [
                'nullable',
                'url',
                'max:255',
                'required_if:listing_type,website',
            ],
            'listing_type' => ['required', Rule::in(['manual', 'website'])],
            'gst_number' => [
                'nullable',
                'string',
                'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z][1-9A-Z]Z[0-9A-Z]$/i',
                'max:15',
            ],
            'address' => ['nullable', 'string', 'max:1000'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:5000'],
            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
                'dimensions:max_width=2000,max_height=2000',
            ],
            'banner' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
                'dimensions:max_width=3000,max_height=1500',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'website_url.required_if' => 'Website URL is required when website integration is selected.',
            'gst_number.regex' => 'Please enter a valid GSTIN.',
            'logo.max' => 'Logo size must not exceed 2 MB.',
            'banner.max' => 'Banner size must not exceed 4 MB.',
        ];
    }
}
