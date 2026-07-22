<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin')
            ->latest()
            ->paginate(10);

        return response()->json($admins);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        $admin = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'admin',
            'status'   => 'active'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Admin created successfully',
            'data'    => $admin
        ]);
    }

    public function show($id)
    {
        $admin = User::where('role', 'admin')
            ->findOrFail($id);

        return response()->json($admin);
    }

    public function update(Request $request, $id)
    {
        $admin = User::where('role', 'admin')
            ->findOrFail($id);

        $admin->update([
            'name' => $request->name ?? $admin->name,
            'email' => $request->email ?? $admin->email,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Admin updated successfully',
            'data' => $admin
        ]);
    }

    public function destroy($id)
    {
        $admin = User::where('role', 'admin')
            ->findOrFail($id);

        $admin->delete();

        return response()->json([
            'success' => true,
            'message' => 'Admin deleted successfully'
        ]);
    }
}