<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;
use Config;

class OfferBuyersInfo extends Model
{
    protected $table = 'offer_buyers_info';
    protected $fillable = [
        'offer_id',
        'first_name',
        'last_name',
        'photo'
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
            return OfferBuyersInfo::where('id', $data['id'])->update($updateData);
        } else {
            return OfferBuyersInfo::create($data);
        }
    }
}
