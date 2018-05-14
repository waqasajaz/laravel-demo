<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;
use Config;

class Tour extends Model
{
    protected $table = 'visitors_feedback';
    protected $fillable = [
        'name',
        'phone_no',
        'email',
        'message',
        'created_at',
        'schedule_date',
        'property_id',
        'price',

    ];


    public function property()
    {
        return $this->belongsTo('App\properties\PropertyModel','property_id');
    }

}
