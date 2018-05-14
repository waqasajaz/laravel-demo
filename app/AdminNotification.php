<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;
use Config;

class AdminNotification extends Model
{
    protected $table = 'admin_panel_notifications';
    protected $fillable = [
        'user_id',
        'activity_type',
        'activity_text',
        'source_id',
        'read_flag',
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
            return AdminNotification::where('id', $data['id'])->update($updateData);
        } else {
            return AdminNotification::create($data);
        }
    }

    public function user()
    {
        return $this->belongsTo('App\AdminUser','source_id');
    }

    public function creator()
    {
        return $this->belongsTo('App\AdminUser','user_id');
    }
}