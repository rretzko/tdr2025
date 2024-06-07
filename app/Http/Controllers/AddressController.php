<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index()
    {
        return Address::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users'],
            'address1' => ['required'],
            'address2' => ['required'],
            'city' => ['required'],
            'geostate_id' => ['required', 'exists:geostates'],
            'postal_code' => ['required'],
        ]);

        return Address::create($data);
    }

    public function show(Address $address)
    {
        return $address;
    }

    public function update(Request $request, Address $address)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users'],
            'address1' => ['required'],
            'address2' => ['required'],
            'city' => ['required'],
            'geostate_id' => ['required', 'exists:geostates'],
            'postal_code' => ['required'],
        ]);

        $address->update($data);

        return $address;
    }

    public function destroy(Address $address)
    {
        $address->delete();

        return response()->json();
    }
}
