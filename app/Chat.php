<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;
use Config;

class Chat extends Model
{
    protected $table = 'chats';
    protected $fillable = [
        'user_id',
        'asset_id',
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
            return Chat::where('id', $data['id'])->update($updateData);
        } else {
            return Chat::create($data);
        }
    }

    public function getAll($filters = array())
    {
        $chats = Chat::orderBy('created_at', 'DESC');

        if(isset($filters) && !empty($filters)) {
            if(isset($filters['asset_id'])){
                $chats->whereIn('asset_id', $filters['asset_id']);
            }

            if(isset($filters['date_range'])){
                $chats->whereBetween('created_at', $filters['date_range']);
            }

            if(isset($filters['read_flag_admin'])){
                $chats->where('read_flag_admin', $filters['read_flag_admin']);
            }

            if(isset($filters['read_flag_agent'])){
                $chats->where('read_flag_agent', $filters['read_flag_agent']);
            }

            if(isset($filters['property_deal'])){
                $chats->whereHas('property', function($q) use($filters) {
                    $q->where('property_deal', $filters['property_deal']);
                });
            }
        }

        return $chats->get();
    }

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

    public function property()
    {
        return $this->belongsTo('App\properties\PropertyModel','asset_id');
    }

    public function messages()
    {
        return $this->hasMany('App\ChatMessage');
    }

    public function agent()
    {
        return $this->belongsTo('App\AdminUser','agent_id');
    }
}
