<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Transaction extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'amount', 'user_id',
    ];


    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }


}
