<?php

namespace App\Http\Controllers;

use App\Events\makeLike;
use Auth;
use App\Models\User;
use App\Models\Status;
use Image;
use Storage;
use Illuminate\Http\Request;

class StatusController extends Controller {

    public function postStatus(Request $request){
        $this->validate($request, [
            'status'  => 'required|max:1000',
            'image'   => 'sometimes|image'
        ]);
        //dd($request->input('isPublic'));
        $is_public = 0;
        if($request->input('isPublic') == 'on')
            $is_public = 1;

        $filename = null;
        if($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('images/' . $filename);
            Image::make($image)->resize(400, 400, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($location);
        }

        Auth::user()->statuses()->create([
            'body'  => $request->input('status'),
            'is_public' => $is_public,
            'image' => $filename,
        ]);
        return redirect()->back()->with('info','Status Posted.');
    }

    public function getStatus($statusId){
        $users = User::where('id', '-1');
        $statuses = Status::where('id', $statusId)->get();
        
        return view('search.results')->with('users', $users)->with('statuses', $statuses);
    }

    public function postReply(Request $req, $statusId) {
        $this->validate($req, [
            "reply-{$statusId}" => "required|max:1000",
        ],[
            'required'  => 'The reply body is required.',
        ]);
        $status = Status::notReply()->find($statusId);
        if(!$status) {
            return redirect()->route('home');
        }
        if(!Auth::user()->isFriendsWith($status->user) && Auth::user()->id !== $status->user->id) {
            return redirect()->route('home');
        }

        $reply = Status::create([
            'body'	=> $req->input("reply-{$statusId}"),
            'user_id' => '0',
        ])->user()->associate(Auth::user());
        $status->replies()->save($reply);
        $returnHTML = view('profile.partials.reply-example')->with('reply', $reply)->render();
        if($req->ajax())
            return response()->json(['new_body' => $reply->body, 'html' => $returnHTML], 200);
        return redirect()->back();
    }

    public function postLike(Request $request, $statusId) {
        $status = Status::find($statusId);

        if(!$status) {
            return redirect()->route('home');
        }

        if(!Auth::user()->isFriendsWith($status->user)){
            return redirect()->route('home');
        }

        if(Auth::user()->hasLikedStatus($status)){
            // dd('has already liked status.');
            $status->likes()->where('user_id', Auth::user()->id)->delete();
            $likesCount = $status->likes->count();
            $statusType = 'status';
            if($status->parent_id) {
                $statusType = 'reply';
            }
            return response()->json(['like_status' => 'unlike', 'likes_count' => $likesCount, 'status_type' => $statusType], 200);
            //return redirect()->back();
        }
        event(new makeLike(Auth::user()->first_name . ' ' . Auth::user()->last_name . ' has liked your post', Auth::user()->username, $status->id));
        //dd(Auth::user()->hasLikedStatus($status));
        $like = $status->likes()->create(['user_id' => '0']);
        Auth::user()->likes()->save($like);
        $likesCount = $status->likes->count();
        $statusType = 'status';
        if($status->parent_id) {
            $statusType = 'reply';
        }
        return response()->json(['like_status' => 'like', 'likes_count' => $likesCount, 'status_type' => $statusType], 200);
        //return redirect()->back();
    }

    public function postEditStatus(Request $request, $statusId) {
        $this->validate($request, [
            'body'  => 'required|max:1000',
            'image' => 'sometimes|image'
        ]);
        $status = Status::find($statusId);

        $is_public = 0;
        if($request['isPublic'] == '1')
            $is_public = 1;

        $filename = '0';
        if($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('images/' . $filename);
            Image::make($image)->resize(400, 400, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($location);
            $oldFilename = $status->image;
            Storage::delete($oldFilename);
            $status->image = $filename;
        }

        if(Auth::user() != $status->user)
        {
            return redirect()->back();
        }
        $status->body = $request['body'];
        if(!$status->parent_id)
            $status->is_public = $is_public;
        $status->update();
        return response()->json(['new_body' => $status->body, 'is_public' => $is_public, 'new_image' => $filename], 200);
    }

    public function getDeleteStatus($statusId) {
        $status = Status::find($statusId);
        if(Auth::user() != $status->user)
        {
            return redirect()->back();
        }
        if(Auth::user()->profile_picture != $status->image)
            Storage::delete($status->image);
        $status->delete();
        if($status->parent_id)
            return redirect()->back()->with('info', 'Reply Deleted.');
        return redirect()->back()->with('info', 'Status Deleted.');
    }

    public function getStatusLikers($statusId) {
        $status = Status::find($statusId);
        $userIds = $status->likes()->pluck('user_id');
        $likers = User::whereIn('id', $userIds)->get();
        $returnHTML = view('user.partials.alluserblock')->with('users', $likers)->render();
        $statusType = 'status';
        $someoneLikes = 'true';
        if($status->parent_id) {
            $statusType = 'reply';
        }
        if(!count($likers)) {
            $someoneLikes = 'false';
        }
        return response()->json(['users' => $likers, 'html' => $returnHTML, 'status_type' => $statusType, 'someone_likes' => $someoneLikes], 200);
    }


}

?>