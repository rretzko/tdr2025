<?php

namespace App\Http\Controllers;

use App\Models\epaymentCredentials;
use Illuminate\Http\Request;

class ePaymentCredentialsController extends Controller
{
    public function index()
    {
        return epaymentCredentials::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'event_id' => ['required', 'exists:versions'],
            'version_id' => ['required'],
            'ePaymentId' => ['required'],
        ]);

        return epaymentCredentials::create($data);
    }

    public function show(epaymentCredentials $ePaymentCredentials)
    {
        return $ePaymentCredentials;
    }

    public function update(Request $request, epaymentCredentials $ePaymentCredentials)
    {
        $data = $request->validate([
            'event_id' => ['required', 'exists:versions'],
            'version_id' => ['required'],
            'ePaymentId' => ['required'],
        ]);

        $ePaymentCredentials->update($data);

        return $ePaymentCredentials;
    }

    public function destroy(epaymentCredentials $ePaymentCredentials)
    {
        $ePaymentCredentials->delete();

        return response()->json();
    }
}
