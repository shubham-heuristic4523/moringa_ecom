<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Public: storefront branding. Pass ?store={slug} for a specific
     * admin's storefront; omit it for the main site ("/"), which shows
     * the super_admin's branding. No auth required — this is what public
     * pages theme themselves from.
     */
    public function show(Request $request)
    {
        if ($request->filled('store')) {
            $admin = User::where('store_slug', $request->query('store'))
                ->whereIn('role', ['admin', 'super_admin'])
                ->first();

            if (! $admin) {
                return response()->json([
                    'status' => false,
                    'message' => 'Store not found.',
                ], 404);
            }

            $adminId = $admin->id;
        } else {
            $adminId = User::where('role', 'super_admin')->value('id');
        }

        $settings = SiteSetting::forAdmin($adminId);

        return response()->json([
            'status' => true,
            'data' => $settings,
            'admin_id' => $adminId,
        ]);
    }
}
