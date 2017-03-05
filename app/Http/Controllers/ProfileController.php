<?php
namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Models\Phone;
use App\Models\Status;
use Image;
use Storage;
use Illuminate\Http\Request;

class ProfileController extends Controller {

    public function getProfile($username) {
        $user = User::where('username', $username)->first();
        
        if(!$user)
            abort(404);

        if(Auth::user()->isFriendsWith($user) || Auth::user() == $user)
            $statuses = $user->statuses()->notReply()->orderBy('created_at', 'desc')->paginate(10);
        else
            $statuses = $user->statuses()->notReply()->where('is_public', '1')->orderBy('created_at', 'desc')->paginate(10);
        return view('profile.index')->with('user', $user)
            ->with('statuses', $statuses)
            ->with('authUserIsFriend', Auth::user()->isFriendsWith($user));
    }

    public function getEdit(){
        return view('profile.edit');
    }
    public function postEdit(Request $request){
        $this->validate($request, [
            'first_name' => 'required|alpha|max:50',
            'last_name'  => 'required|alpha|max:50',
            'nickname'  => 'alpha|max:50',
            'hometown'   => 'max:30',
            'profile_picture'   => 'sometimes|image'
        ]);

        $is_female = 0;
        if($request->input('gender') == 'on')
            $is_female = 1;
        $is_avatar = false;
        if(Auth::user()->profile_picture == 'female_avatar.jpg' || Auth::user()->profile_picture == 'male_avatar.jpg')
        {
            $is_avatar = true;
            if($is_female)
                Auth::user()->update([
                    'profile_picture' => 'female_avatar.jpg'
                ]);
            else
                Auth::user()->update([
                    'profile_picture' => 'male_avatar.jpg'
                ]);
        }

        $filename = Auth::user()->profile_picture;
        if($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('images/' . $filename);
            Image::make($image)->resize(400, 400, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($location);
            if(!$is_avatar) {
                $oldFilename = Auth::user()->profile_picture;
                Storage::delete($oldFilename);
            }
            Auth::user()->statuses()->create([
                'body'  => Auth::user()->getNicknameOrFullnameOrUsername() . ' has changed profile picture',
                'is_public' => '0',
                'image' => $filename,
            ]);
        }

        Auth::user()->update([
            'first_name'  => $request->input('first_name'),
            'last_name'  => $request->input('last_name'),
            'nickname'  => $request->input('nickname'),
            'gender'  => $is_female,
            'marital_status'  => $request->input('marital_status'),
            'about'  => $request->input('about'),
            'birth_date' => $request->input('birth_date'),
            'profile_picture' => $filename,
            'hometown'  => $request->input('hometown'),
        ]);

        Auth::user()->phones()->where('is_phone_number_1', '1')->update([
            'phone_number'	=> $request->input("phone_number_1"),
        ]);

        Auth::user()->phones()->where('is_phone_number_1', '0')->update([
            'phone_number'	=> $request->input("phone_number_2"),
        ]);

        return redirect()->route('profile.edit')
            ->with('info', 'Your profile has been updated.');
    }

    public function postDeletePhoto(){

        Storage::delete(Auth::user()->profile_picture);
        $profilePicturePost = Auth::user()->statuses()->where('image', Auth::user()->profile_picture)->first();
        $profilePicturePost->delete();
        if(Auth::user()->gender == '1')
            Auth::user()->update([
               'profile_picture' => 'female_avatar.jpg'
            ]);
        else
            Auth::user()->update([
                'profile_picture' => 'male_avatar.jpg'
            ]);
        return redirect()->route('profile.edit')
            ->with('info', 'Your profile has been updated.');
    }
}

?>