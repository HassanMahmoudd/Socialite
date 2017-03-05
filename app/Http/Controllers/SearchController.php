<?php
namespace App\Http\Controllers;

use DB;
use App\Models\User;
use App\Models\Status;
use Auth;
use Illuminate\Http\Request;

class SearchController extends Controller {

    public function getResults(Request $request){
        $query = '%'.$request->input('query').'%';
        if(!$query)
            return redirect()->route('home');
        $users = User::where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', $query)
            ->orWhere('username', 'LIKE', $query)
            ->orWhere('hometown', 'LIKE', $query)
            ->orWhere('email', 'LIKE', $query)
            ->get();

        $statuses = Auth::user()->statuses()->notReply()->where('body', 'LIKE', $query)->get();
        return view('search.results')->with('users', $users)->with('statuses', $statuses);
    }
}

?>