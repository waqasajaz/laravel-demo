<?php

namespace App\collection;

use Illuminate\Database\Eloquent\Model;

class GrouplistModel extends Model
{
	protected $table = "property_in_collections";

	protected $fillable = array('collection_id', 'property_id', 'user_id', 'property_from', 'comment', 'reference_id', 'invited_by', 'created_at', 'updated_at');

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
