<?php

namespace App\Http\Controllers\Registration;


use App\Http\Controllers\Controller;
use App\Actions\Fortify\PasswordValidationRules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Hash,
    Validator,
};
use App\Models\{
    Invite,
    User,
};
use Symfony\Component\HttpFoundation\Response;


class RegisterController extends Controller
{
    use PasswordValidationRules;

    public function create(Request $request){
        
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
        ]);

        User::create([
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'suffix' => $request->input('suffix') ?? null,
            'date_of_birth' => $request->input('date_of_birth'),
            'role_id' => 3,
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        $invite = Invite::where('token', $request->input('token'))->first();
        $invite->update(['status' => 1]);

        return redirect()->route('login')->with('success', 'Account Creation Successful. Please Login to continue.');
    }
}
