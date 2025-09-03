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
use Illuminate\Support\Str;
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
        //force email into all lower case
        $request['email'] = Str::lower($request['email']);

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

        //set user as teacher prior to School being identified
        Teacher::create(['id' => $user->id, 'user_id' => $user->id]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('school.create');
//        return redirect()->intended(route('school.create'));
    }

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }
}
