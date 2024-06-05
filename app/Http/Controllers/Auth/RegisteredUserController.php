<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Schools\Teacher;
use App\Models\User;
use App\Services\SplitNameIntoNamePartsService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     * A new user should be immediately directed to create a school link
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $service = new SplitNameIntoNamePartsService($request->name);
        $names = $service->getNameParts();

        $user = User::create([
            'name' => $request->name,
            'prefix_name' => $names['prefix_name'],
            'first_name' => $names['first_name'],
            'middle_name' => $names['middle_name'],
            'last_name' => $names['last_name'],
            'suffix_name' => $names['suffix_name'],
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        //set user as teacher
        Teacher::create(['id' => $user->id, 'user_id' => $user->id]);

        event(new Registered($user));

        Auth::login($user);

        //return redirect()->intended(route('home'));
        return redirect()->intended(route('school.create'));
    }

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    private function parseName(string $name): array
    {
        $parts = explode(' ', $name);

        $a['first_name'] = $parts[0];
        $a['last_name'] = $parts[1];

        return $a;
    }
}
