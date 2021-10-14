<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Invite;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Notifications\InviteNotification;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required'],
        ]);

        User::create([
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'suffix' => $request->input('suffix') ?? null,
            'date_of_birth' => $request->input('date_of_birth'),
            'role_id' => $request->input('role_id'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        return redirect()->route('users.index')->with('success','User added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required'],
        ]);
        $user->update([
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'suffix' => $request->input('suffix') ?? null,
            'date_of_birth' => $request->input('date_of_birth'),
            'role_id' => $request->input('role_id'),
        ]);

        return redirect()->route('users.index')->with('success','User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }

    // public function invite(){
    //     $invites = Invite::all();
    //     return view('users.invite', compact('invites'));
    // }

    // public function send(Request $request){
    //     $request->validate([
    //         'email' => ['required', 'email', 'max:255', 'unique:users', 'unique:invites'],
    //     ]);
    //     do{
    //         $token = Str::random(20);
    //     } while (Invite::where('token', $token)->first());

    //     Invite::create([
    //         'token' => $token,
    //         'email' => $request->input('email'),
    //         'status' => 0,
    //     ]);

    //     $url = URL::temporarySignedRoute(
    //         'registration', now()->addMinutes(300), ['token' => $token]
    //     );
    //     Notification::route('mail', $request->input('email'))->notify(new InviteNotification($url));
    //     return redirect()->route('admin.users.invite')->with('success', 'User invited successfully');
    // }

    // public function registration_view($token){
    //     $invite = Invite::where('token', $token)->first();
    //     if($invite === null){
    //         return abort("403");
    //     }
    //     elseif($invite->status != 0){
    //         return redirect()->route('login')->with('register-error','Email already registered');
    //     }
    //     return view('auth.register', ['invite' => $invite]);
    // }
    
}
