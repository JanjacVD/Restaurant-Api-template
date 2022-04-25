<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\ContactMail;

class ContactController extends Controller
{
    public function send(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255'],
                'subject' => ['required', 'string', 'max:255'],
                'message' => ['required', 'min:3', 'max:1000']
            ]
        );
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json(['Status' => 'Invalid data', 'Errors' => $errors], 422);
        } 
        $details = [
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ];

        Mail::to(env('CONTACT_MAIL'))->send(new ContactMail($details));

        return response()->json(['Status' => 'Email sent'], 202);
    }
}
