<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;
use Config;

class PropertyInvitation extends Model
{
    protected $table = 'property_invitations';
    protected $fillable = [
        'collection_id',
        'property_id',
        'from_user',
        'to_email',
        'token',
    ];

    public function insertUpdate($data)
    {
        if (isset($data['id']) && $data['id'] != '' && $data['id'] > 0) {
            $updateData = [];
            foreach ($this->fillable as $field) {
                if (array_key_exists($field, $data)) {
                    $updateData[$field] = $data[$field];
                }
            }
            return PropertyInvitation::where('id', $data['id'])->update($updateData);
        } else {
            return PropertyInvitation::create($data);
        }
    }

    public function user()
    {
        return $this->belongsTo('App\User','from_user');
    }
}
