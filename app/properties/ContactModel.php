<?php

namespace App\properties;

use Illuminate\Database\Eloquent\Model;

class ContactModel extends Model
{
	protected $table = "property_contact";

	protected $fillable = array(
		"property",
		"contact_name",
		"contact_phone",
		"contact_email",
		"duration",
		"created_at",
	);

	public function property_contact()
	{
		return $this->belongsTo("App\properties\PropertyModel")->withDefault([
			'contact_name' => '',
			'contact_phone' => '',
			'contact_email' => '',
			'duration' => '',
		]);
	}
}