<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;
use Config;

class PropertyChat extends Model
{
    protected $table = 'property_chats';
    protected $fillable = [
        'property_id'
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
            return PropertyChat::where('id', $data['id'])->update($updateData);
        } else {
            return PropertyChat::create($data);
        }
    }

    public function property()
    {
        return $this->belongsTo('App\properties\PropertyModel','property_id');
    }

    public function messages()
    {
        return $this->hasMany('App\PropertyChatMessage', 'chat_id');
    }
}
