<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchedulsModel extends Model
{
    protected $table = "scheduled_visits";

	protected $fillable = [
		"property",
		"scheduled_dates"
	];

	public function property()
	{
		return $this->belongsTo('App\properties\PropertyModel','property');
	}
}
