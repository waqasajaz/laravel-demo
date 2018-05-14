<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;
use Config;

class Offer extends Model
{
    protected $table = 'offers';
    protected $fillable = [
        'login_id',
        'asset_id',
        'payment_method',
        'payment_method_mortgage',
        'payment_method_mortgage_dp_amount',
        'payment_method_mortgage_certificate',
        'proof_of_income',
        'step_1_completed',
        'step_2_negotiate_flag',
        'owner_offer_price',
        'customer_offer_price',
        'step_2_completed',
        'customer_name',
        'customer_phone',
        'customer_email',
        'step_3_completed',
        'schedule_visit_1',
        'schedule_visit_2',
        'mark_as_visited',
        'rental_period',
        'step_4_completed',
        'step_5_completed',
        'first_name_1',
        'last_name_1',
        'photo_1',
        'first_name_2',
        'last_name_2',
        'photo_2',
        'step_6_completed',
        'signature_schedule_1',
        'signature_schedule_2',
        'signature_schedule_3',
        'step_7_completed',
        'accept_status',
        'new',
        'new_completed'
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
            return Offer::where('id', $data['id'])->update($updateData);
        } else {
            return Offer::create($data);
        }
    }

    public function getAll($filters = array())
    {
        $offers = Offer::select(DB::raw("offers.*, HO.hood, PR.cops, DT.dist_name, PR.sizem2, PC.contact_name, PC.contact_phone, PC.contact_email"));

	    $offers = $offers->leftjoin("property as PR", "PR.id", "=", "offers.asset_id");
	    $offers = $offers->leftjoin("hoods as HO", "HO.id", "=", "PR.hoods");
	    $offers = $offers->leftjoin("district as DT", "DT.id", "=", "PR.dist_id");
	    $offers = $offers->leftjoin("property_contact as PC", "PC.property", "=", "PR.id");

	    $offers = $offers->orderBy('id', 'DESC');

        if(isset($filters) && !empty($filters)) {
            if(isset($filters['new'])){
                $offers->where('new', $filters['new']);
            }

            if(isset($filters['new_completed'])){
                $offers->where('new_completed', $filters['new_completed']);
            }

            if(isset($filters['asset_id'])){
                $offers->whereIn('asset_id', $filters['asset_id']);
            }

            if(isset($filters['step_7_completed'])){
                $offers->where('step_7_completed', $filters['step_7_completed']);
            }

            if(isset($filters['accept_status'])){
                $offers->where('accept_status', $filters['accept_status']);
            }

            if(isset($filters['property_deal']) && $filters['property_deal'] != ''){
                $offers->whereHas('property', function($q) use($filters) {
                    $q->where('property_deal', $filters['property_deal']);
                });
            }
        }
        $offers->where("sold_status", "!=", 1);

        return $offers->get();
    }

    public function user()
    {
        return $this->belongsTo('App\User','login_id');
    }

    public function property()
    {
        return $this->belongsTo('App\properties\PropertyModel','asset_id');
    }

}
