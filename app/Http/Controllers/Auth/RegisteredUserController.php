<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        if (Auth::user()->role == 'Admin') {
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                    'role' => ['required', 'string', 'max:255'],
                    'password' => ['required', 'confirmed', Rules\Password::defaults()],
                ]
            );

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['Status' => 'Invalid data', 'Errors' => $errors], 422);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'password' => Hash::make($request->password),
            ]);

            event(new Registered($user));

            return response()->json(['Status' => 'User created successfully', 'User' => $user], 201 );
        }
        else{
            return response()->json(['Status' => 'Unauthorized'], 401 );
        }
    }
}
