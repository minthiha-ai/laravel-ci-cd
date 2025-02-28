<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display the authenticated user details.
     */
    public function show(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Update the authenticated user's profile.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validatedData = $request->validate([
            'name' => 'string|max:255',
            'email' => ['email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'state_region_id' => 'nullable|exists:state_regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'township_id' => 'nullable|exists:townships,id',
            'postal_code' => 'nullable|string|max:10',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'active' => 'boolean',
        ]);

        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        $user->update($validatedData);

        return response()->json(['message' => 'Profile updated successfully', 'user' => $user]);
    }

    /**
     * Get the user's associated types.
     */
    public function userTypes(Request $request)
    {
        $types = $request->user()->types()->get();
        return response()->json($types);
    }

    /**
     * Get user's location details (State, District, Township).
     */
    public function location(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'state_region' => $user->stateRegion,
            'district' => $user->district,
            'township' => $user->township,
        ]);
    }
}
