<?php
namespace App\Models;

use App\Models\Status;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class User extends Model implements AuthenticatableContract{
    use Authenticatable;
   
    protected $table = 'users';
    
    protected $fillable = ['email', 'username', 'password', 'first_name', 'last_name', 'hometown', 'nickname', 'gender', 'marital_status', 'about', 'birth_date', 'profile_picture'];
    
    protected $hidden = ['password', 'remember_token'];

    public function getNicknameOrFullnameOrUsername() {
        if($this->nickname)
            return $this->nickname;
        elseif ($this->first_name && $this->last_name)
            return "{$this->first_name} {$this->last_name}";
        return $this->username;
    }

    public function statuses() {
        return $this->hasMany('App\Models\Status', 'user_id');
    }

    public function likes(){
        return $this->hasMany('App\Models\Like', 'user_id');
    }

    public function phones(){
        return $this->hasMany('App\Models\Phone', 'user_id');
    }

    public function friendsOfMine(){
        return $this->belongsToMany('App\Models\User', 'friends', 'user_id', 'friend_id');
    }

    public function friendOf(){
        return $this->belongsToMany('App\Models\User', 'friends', 'friend_id', 'user_id');
    }

    public function friends(){
        return $this->friendsOfMine()->wherePivot('accepted', true)->get()
            ->merge($this->friendOf()->wherePivot('accepted', true)->get());
    }

    public function notFriends(){
//        $friends = $this->friendsOfMine()->get()
//            ->merge($this->friendOf()->get());
//        return User::whereNotIn('id', $friends->pluck('id'))->where('id', '!=', $this->id)->get();
        $friends = $this->friends();
        $totalHisFriends = collect([]);
        foreach($friends as $friend)
        {
            $hisFriends = $friend->friends();
            $totalHisFriends->merge($hisFriends);
        }
        //dd($totalHisFriends);
        $friends = $this->friendsOfMine()->get()
            ->merge($this->friendOf()->get());
        return User::whereNotIn('id', $friends->pluck('id'))->where('id', '!=', $this->id)->get();
    }

    public function friendRequests(){
        return $this->friendsOfMine()->wherePivot('accepted', false)->get();
    }

    public function friendRequestsPending() {
        return $this->friendOf()->wherePivot('accepted', false)->get();
    }

    public function hasFriendRequestPending(User $user) {
        return (bool) $this->friendRequestsPending()->where('id', $user->id)->count();
    }

    public function hasFriendRequestRecieved(User $user){
        return (bool) $this->friendRequests()->where('id', $user->id)->count();
    }

    public function addFriend(User $user){
        $this->friendOf()->attach([$user->id => ['accepted'=>'0']]);
    }

    public function deleteFriend(User $user){
        $this->friendOf()->detach($user->id);
        $this->friendsOfMine()->detach($user->id);
    }

    public function rejectFriend(User $user){
        
        $this->friendsOfMine()->detach($user->id);
    }

    public function acceptFriendRequest(User $user) {
        $this->friendRequests()->where('id', $user->id)->first()->pivot
            ->update([
                'accepted'=>true,
            ]);
    }

    public function isFriendsWith(User $user) {

        return (bool) $this->friends()->where('id',$user->id)->count();
    }

    public function hasLikedStatus(Status $status) {
        return (bool) $status->likes()->where('user_id', $this->id)->count();
    }
}
?>