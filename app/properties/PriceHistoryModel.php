<?php

namespace App\properties;

use Illuminate\Database\Eloquent\Model;

class PriceHistoryModel extends Model
{
    protected $table = "price_history";

	protected $fillable = [
		"property",
		"price",
		"loquare_commission",
		"realestate_commission",
		"created_at",
		"updated_at"
	];

	function property()
	{
		return $this->belongsTo('App\proeprties\PropertyModel', 'proeprty');
	}
}
