<?php

namespace App\Http\Controllers;
use Auth;
use App\Models\Status;
use App\Models\User;

class HomeController extends Controller {
    public function index(){

        if(Auth::check()){
            $statuses = Status::notReply()->where(function($query){
                return $query->where('user_id', Auth::user()->id)
                    ->orWhereIn('user_id', Auth::user()->friends()->pluck('id'));
            })->orderBy('created_at', 'desc')
                ->paginate(10);

            $people = Auth::user()->notFriends();
            // dd($people);
            return view('timeline.index')->with('statuses', $statuses)->with('people', $people);

        }
        return view('home');
    }
}

?>