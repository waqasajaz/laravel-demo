<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;
use Config;

class AddpropertyHelp extends Model
{
	protected $table = 'add_property_help';
	protected $fillable = array(
		"fullname",
		"phon_no",
		"email",
		"message",
		"created_at",
		"updated_at"
	);

}
