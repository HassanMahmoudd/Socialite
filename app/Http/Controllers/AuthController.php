<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Phone;

class AuthController extends Controller {
    public function getSignup(){
        return view('auth.signup');
    }
    public function postSignup(Request $request){
        $this->validate($request, [
            'email' => 'required|unique:users|email|max:20',
            'username' => 'required|unique:users|alpha_dash|max:20',
            'password' => 'required|min:6',
            'first_name' => 'required|alpha|max:20',
            'last_name' => 'required|alpha|max:20',
            'birth_date' => 'required',
            'profile_picture' => 'sometimes|image'
        ]);

        $is_female = 0;
        $filename = 'male_avatar.jpg';
        if($request->input('gender') == 'on') {
            $is_female = 1;
            $filename = 'female_avatar.jpg';
        }

        if($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('images/' . $filename);
            Image::make($image)->resize(400, 400, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($location);
        }

        $user = User::create([
            'email'  =>  $request->input('email'),
            'username'  =>  $request->input('username'),
            'password'  =>  bcrypt($request->input('password')),
            'first_name'  =>  $request->input('first_name'),
            'last_name'  =>  $request->input('last_name'),
            'nickname'  =>  $request->input('nickname'),
            'birth_date'  =>  $request->input('birth_date'),
            'gender' => $is_female,
            'profile_picture' => $filename,
            'hometown'  =>  $request->input('hometown'),
            'about' => $request->input('about'),
            'marital_status' => $request->input('marital_status')
        ]);

        $user->phones()->create([
            'phone_number'	=> $request->input("phone_number_1"),
            'is_phone_number_1' => '1',
        ]);

        $user->phones()->create([
            'phone_number'	=> $request->input("phone_number_2"),
            'is_phone_number_1' => '0',
        ]);

        if(! Auth::attempt($request->only(['email', 'password']))) {
            return redirect()->back()->with('info', 'Invalid email or password');
        }
        return redirect()->route('home')->with('info', 'Your account has been created & you are Signed in Now');

        //return redirect()->route('home')->with('info' , 'Your account has been created and you can sign in now.');
    }

    public function getSignin(){
        return view('auth.signin');
    }
    public function postSignin(Request $request) {
        $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required|min:6'
        ]);

        if(! Auth::attempt($request->only(['email', 'password']), $request->has('remember'))) {
            return redirect()->back()->with('info', 'Invalid email or password');
        }
        return redirect()->route('home')->with('info', 'You are Signed in Now');
    }

    public function getSignout(){
        Auth::logout();
        return redirect()->route('home')->with('info', 'Signed out successfully');
    }
}

?>