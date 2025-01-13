<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\PhoneNumber;
use App\Services\FormatPhoneService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
            'dto' => ['header' => 'profile'],
        ]);
    }

    public function phoneUpdate(Request $request): RedirectResponse
    {
        $this->phoneUpdatePhone('mobile', $request->all());
        $this->phoneUpdatePhone('work', $request->all());

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    private function phoneUpdatePhone(string $phoneType, array $request): void
    {
        $key = 'phone'.ucfirst($phoneType);
        $phoneNumber = '';
        $service = new FormatPhoneService();

        if (array_key_exists($key, $request)) {
            if (strlen($request[$key])) {
                $phoneNumber = $service->getPhoneNumber($request[$key]);
            }
        }
        PhoneNumber::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'phone_type' => $phoneType,
            ],
            [
                'phone_number' => $phoneNumber,
            ]
        );
    }
}
