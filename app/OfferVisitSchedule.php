<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;
use Config;

class OfferVisitSchedule extends Model
{
    protected $table = 'offer_visit_schedule';
    protected $fillable = [
        'offer_id',
        'datetime',
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
            return OfferVisitSchedule::where('id', $data['id'])->update($updateData);
        } else {
            return OfferVisitSchedule::create($data);
        }
    }
}
