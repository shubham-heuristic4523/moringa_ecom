<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    /**
     * The logged-in admin's own branding, for populating the settings form.
     */
    public function show(Request $request)
    {
        return response()->json([
            'status' => true,
            'data' => SiteSetting::forAdmin($request->user()->id),
        ]);
    }

    /**
     * Update the logged-in admin's own branding. Each admin only ever
     * touches their own row — never another admin's or the platform default.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'site_name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:500',
            'logo' => 'nullable|image|max:2048',
            'primary_color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'secondary_color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'accent_color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',

            'referral_discount_type' => 'required|in:percentage,flat',
            'referral_discount_value' => [
                'required', 'numeric', 'min:0.01',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->input('referral_discount_type') === 'percentage' && $value > 100) {
                        $fail('Percentage discount cannot exceed 100.');
                    }
                },
            ],
            'referral_max_discount_amount' => 'nullable|numeric|min:0.01',
            'referral_validity_days' => 'required|integer|min:1|max:365',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $settings = SiteSetting::forAdmin($request->user()->id);

        if ($request->hasFile('logo')) {
            if ($settings->logo) {
                Storage::disk('public')->delete($settings->logo);
            }

            $data['logo'] = $request->file('logo')->store('branding', 'public');
        }

        $settings->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Settings updated successfully.',
            'data' => $settings->fresh(),
        ]);
    }
}
