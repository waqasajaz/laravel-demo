<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DistrictGalleryModel extends Model
{
	protected $table = "district_gallery";

	protected $fillable = array('dist_id', 'image');
}
