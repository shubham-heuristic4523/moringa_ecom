<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Services\CustomerService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected CustomerService $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    /**
     * Get Customer Profile
     */
    public function getProfile(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $this->customerService->getProfile($request->user())
        ]);
    }

    /**
     * Update Customer Profile
     */
    public function updateProfile(Request $request)
    {

        $validated = $request->validate([

            'name' => 'sometimes|string|max:255',

            'gender' => 'nullable|in:male,female,other',

            'date_of_birth' => 'nullable|date',

            'company_name' => 'nullable|string|max:255',

            'gst_number' => 'nullable|string|max:30',

            'bio' => 'nullable|string|max:1000',

            'profile_photo' => 'nullable|image|max:2048'

        ]);

        return response()->json([

            'success' => true,

            'message' => 'Profile updated successfully.',

            'data' => $this->customerService->updateProfile(
                $request->user(),
                $validated
            )

        ]);
    }
    /**
     * Store Address
     */
    public function storeAddress(Request $request)
    {
        $validated = $request->validate([

            'full_name' => 'required|string|max:255',

            'phone' => 'required|string|max:20',

            'alternate_phone' => 'nullable|string|max:20',

            'address_type' => 'required|in:home,office,other',

            'address_line_1' => 'required|string|max:255',

            'address_line_2' => 'nullable|string|max:255',

            'landmark' => 'nullable|string|max:255',

            'city' => 'required|string|max:100',

            'state' => 'required|string|max:100',

            'country' => 'nullable|string|max:100',

            'postal_code' => 'required|string|max:10',

            'is_default' => 'boolean'

        ]);

        return response()->json([

            'success' => true,

            'message' => 'Address added successfully.',

            'data' => $this->customerService->storeAddress(

                $request->user(),

                $validated

            )

        ], 201);
    }
    /**
     * List Customer Addresses
     */
    public function listAddresses(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $this->customerService->listAddresses(
                $request->user()
            )
        ]);
    }
    /**
     * Get Address
     */
    public function getAddress(Request $request, $id)
    {
        return response()->json([
            'success' => true,
            'data' => $this->customerService->getAddress(
                $request->user(),
                $id
            )
        ]);
    }
    /**
     * Update Address
     */
    public function updateAddress(Request $request, $id)
    {
        $validated = $request->validate([

            'full_name' => 'required|string|max:255',

            'phone' => 'required|string|max:20',

            'alternate_phone' => 'nullable|string|max:20',

            'address_type' => 'required|in:home,office,other',

            'address_line_1' => 'required|string|max:255',

            'address_line_2' => 'nullable|string|max:255',

            'landmark' => 'nullable|string|max:255',

            'city' => 'required|string|max:100',

            'state' => 'required|string|max:100',

            'country' => 'nullable|string|max:100',

            'postal_code' => 'required|string|max:10',

            'is_default' => 'boolean'

        ]);

        return response()->json([
            'success' => true,
            'message' => 'Address updated successfully.',
            'data' => $this->customerService->updateAddress(
                $request->user(),
                $id,
                $validated
            )
        ]);
    }
    /**
     * Delete Address
     */
    public function deleteAddress(Request $request, $id)
    {
        $this->customerService->deleteAddress(
            $request->user(),
            $id
        );

        return response()->json([
            'success' => true,
            'message' => 'Address deleted successfully.'
        ]);
    }
}
