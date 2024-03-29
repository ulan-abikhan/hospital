<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Mail\VerificationMail;
use App\Models\EmailVerification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function store(UserRequest $request) {
        $photoName = null;

        Log::info("creating user with email: {email}", ["email"=>$request->email]);

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = Str::random(32);
            $mime = $photo->getClientOriginalExtension();
            $photoName = $photoName.'.'.$mime;
            Storage::disk('public')->put("photos/$photoName", file_get_contents($photo));
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

        $this->sendMail($request['first_name'], $request['email'], $photo);

        return response(status: 201);
    }

    public function sendMail($firstName, $email, $photo) {
        
        $token = Str::random(50);

        $emailVerification = EmailVerification::create([
            "token"=>$token,
            "email"=>$email
        ]);

        $link = URL::to('/').'/api/verify-mail?token='.$token;

        $dicardLink = URL::to('/').'/api/dicard-mail?token='.$token;

        Mail::to($email)->send(new VerificationMail($link, $firstName, $dicardLink, $photo));

    }

    public function verify(Request $request) {
        $token = $request['token'];

        $email = EmailVerification::where('token', $token)->first();

        $user = User::where('email', $email['email'])->first();

        $user->email_verified_at = Carbon::now();

        $user->save();

        $email->delete();

        return redirect(URL::to('/'));

    }

    public function discard(Request $request) {
        $token = $request['token'];

        $email = EmailVerification::where('token', $token)->first();

        $user = User::where('email', $email['email'])->first();

        $email->delete();

        $user->delete();

        return redirect(URL::to('/'));
    }

    public function destroy($id) {
        
    }

}