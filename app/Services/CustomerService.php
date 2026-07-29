<?php

namespace App\Services;

use App\Models\User;
use App\Models\Customer\CustomerProfile;
use App\Models\Customer\CustomerAddress;

class CustomerService
{
    /**
     * Get Logged In Customer Profile
     */
    public function getProfile(User $user): array
    {
        $user->load('customerProfile');

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,

            'gender' => $user->customerProfile?->gender,
            'date_of_birth' => $user->customerProfile?->date_of_birth,
            'company_name' => $user->customerProfile?->company_name,
            'gst_number' => $user->customerProfile?->gst_number,
            'bio' => $user->customerProfile?->bio,
            'profile_photo' => $user->customerProfile?->profile_photo,
            'profile_completed' => $user->customerProfile?->profile_completed,
        ];
    }

    /**
     * Update Customer Profile
     */
    public function updateProfile(User $user, array $data): array
    {
        if (isset($data['name'])) {
            $user->update([
                'name' => $data['name'],
            ]);

            unset($data['name']);
        }

        $profile = $user->customerProfile;

        // We'll implement image upload later
        unset($data['profile_photo']);

        $profile->update($data);

        $profile->refresh();

        $profile->update([
            'profile_completed' =>
            !empty($profile->gender) &&
                !empty($profile->date_of_birth)
        ]);

        return $this->getProfile($user->fresh());
    }
    /**
     * Add Customer Address
     */
    public function storeAddress(User $user, array $data)
    {
        if (!empty($data['is_default'])) {

            CustomerAddress::where('user_id', $user->id)

                ->update([

                    'is_default' => false

                ]);
        }

        $data['user_id'] = $user->id;

        return CustomerAddress::create($data);
    }
    /**
     * Get Customer Addresses
     */
    public function listAddresses(User $user)
    {
        return CustomerAddress::where('user_id', $user->id)
            ->orderByDesc('is_default')
            ->latest()
            ->get();
    }
    /**
     * Get Single Address
     */
    public function getAddress(User $user, int $id)
    {
        return CustomerAddress::where('user_id', $user->id)
            ->findOrFail($id);
    }
    /**
     * Update Customer Address
     */
    public function updateAddress(User $user, int $id, array $data)
    {
        $address = CustomerAddress::where('user_id', $user->id)
            ->findOrFail($id);

        // If setting this address as default,
        // remove default from all other addresses.
        if (!empty($data['is_default'])) {

            CustomerAddress::where('user_id', $user->id)
                ->update([
                    'is_default' => false
                ]);
        }

        $address->update($data);

        return $address->fresh();
    }
    /**
     * Delete Customer Address
     */
    public function deleteAddress(User $user, int $id): void
    {
        $address = CustomerAddress::where('user_id', $user->id)
            ->findOrFail($id);

        $address->delete();
    }
}
