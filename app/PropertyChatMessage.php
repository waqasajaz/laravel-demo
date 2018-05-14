<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;
use Config;

class PropertyChatMessage extends Model
{
    protected $table = 'property_chat_messages';
    protected $fillable = [
        'chat_id',
        'user_id',
        'message',
        'read_by'
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
            return PropertyChatMessage::where('id', $data['id'])->update($updateData);
        } else {
            return PropertyChatMessage::create($data);
        }
    }

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

    public function chat()
    {
        return $this->belongsTo('App\Chat','chat_id');
    }
}
