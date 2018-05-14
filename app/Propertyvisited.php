<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;
use Config;

class Propertyvisited extends Model
{
	protected $table = 'property_visited';
	protected $fillable = array(
		'property_id',
		'user_id',
		'created_at',
		'updated_at',
	);

}