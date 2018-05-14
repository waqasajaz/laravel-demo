<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;
use Config;

class ChatMessage extends Model
{
    protected $table = 'chat_messages';
    protected $fillable = [
        'chat_id',
        'message',
        'user_id',
        'agent_id',
        'read_flag',
        'read_flag_admin',
        'read_flag_agent',
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
            return ChatMessage::where('id', $data['id'])->update($updateData);
        } else {
            return ChatMessage::create($data);
        }
    }

    public function getAll()
    {
        $offers = ChatMessage::orderBy('id', 'ASC')->get();
        return $offers;
    }

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

    public function agent()
    {
        return $this->belongsTo('App\AdminUser','agent_id');
    }

    public function property()
    {
        return $this->belongsTo('App\properties\PropertyModel','asset_id');
    }

    public function chat()
    {
        return $this->belongsTo('App\Chat','chat_id');
    }
}
