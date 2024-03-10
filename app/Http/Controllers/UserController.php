<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Mail\VerificationMail;
use App\Mail\WelcomeMail;
use App\Models\EmailVerification;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
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

        $this->sendMail($request['first_name'], $request['email']);

        return response(status: 201);
    }

    public function sendMail($firstName, $email) {
        
        $token = Str::random(50);

        $emailVerification = EmailVerification::create([
            "token"=>$token,
            "email"=>$email
        ]);

        $link = URL::to('/').'/api/verify?token='.$token;

        $dicardLink = URL::to('/').'/api/dicard?token='.$token;

        Mail::to($email)->send(new VerificationMail($link, $firstName, $dicardLink));

    }

}