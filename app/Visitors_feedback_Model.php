<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visitors_feedback_Model extends Model
{
	protected $table = "visitors_feedback";
	protected $fillable = array('name','phone_no','schedule_date','email', 'message','property_id','direccion','price','created_at');	
}
