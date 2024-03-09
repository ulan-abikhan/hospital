<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function store(UserRequest $request) {
        $photoName = null;

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = Str::random(32);
            $mime = $photo->getClientOriginalExtension();
            $photoName = $photoName.'.'.$mime;
        }

        User::create([
            'first_name'=>$request['first_name'],
            'last_name'=>$request['last_name'],
            'middle_name'=>$request['middle_name'],
            'email'=>$request['email'],
            'password'=>bcrypt($request['password']),
            'photo'=>$photoName,
            'role'=>$request['role']
        ]);

        return response(status: 201);
    }


    public function sendMail() {
        $title = 'Welcome to the internet';
        $body = 'Thank you dear!';

        Mail::to('uabikhan@gmail.com')->send(new WelcomeMail($title, $body));

        return "Email sent successfully!";
    }

}