<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model {
    protected $table = 'phones';
    protected $fillable = [
        'phone_number',
        'user_id',
        'is_phone_number_1'
    ];

    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id');
    }

}