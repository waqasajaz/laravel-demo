<?php

namespace App\properties;

use App\Http\Controllers\CI_ModelController;
use App\UploadModel;
use Illuminate\Database\Eloquent\Model;
use DB;

class PropertyModel extends Model
{
	protected $table = "property";

	protected $fillable = array(
		"user_id",
		"published_by",
		"company_id",
		"comunidad_autonoma",
		"direccion",
		"localidad",
		"provincia",
		"cops",
		"property_for",
		"dist_id",
		"state_id",
		"property_type",
		"property_sub_type",
		"rooms",
		"bathrooms",
		"sizem2",
		"property_deal",
		"rent_by",
		"lease_duration",
		"price",
        'price_help_needed',
		"discription",
        "description_help_needed",
        "images_help_needed",
        "documentation_help_needed",
		"usability",
        "favourite_space",
		"about_hood",
		"construction",
		"ref",
		"hoods",
		"elevetor",
		"doorman",
		"furnished",
		"furnished_kitchen",
		"furnished_all",
		"floor",
		"floor_hardwood",
		"floor_ceramic",
		"floor_natural_light",
		"cellings",
		"cellings_high",
		"cellings_other",
		"heating",
		"laundry",
		"central_ac",
		"outdoor_space",
		"gym",
		"dishwasher",
		"pool",
		"pets",
		"dogs",
		"cats",
		"most_relevant",
		"loquare_listing",
		"others",
		"latitude",
		"longitude",
		"status",
		"created_at",
		"updated_at",
		"historical_price1",
		"historical_price_date1",
		"historical_price2",
		"historical_price_date2",
		"historical_price3",
		"historical_price_date3",
		"plisted_d_historical_price3",
		"historical_price2_d_historical_price3",
		"plisted_d_historical_price1",
		"type2",
		"estimated_cost",
		"mortage",
		"mortage_percentage",
		"closing_cost_mortage",
		"cost_mortage",
		"potential_income",
		"offered",
        "admin_notified",
        "deleted"
	);

	protected function property_type($id = "")
	{
		if($id != "")
		{
			$data = DB::table($this->table." as TB")->select("PT.property_type_name as type")
				->leftjoin("property_types as PT", "TB.property_type", "=", "PT.id")
				->where("TB.id", "=", $id)
				->first();

			return $data->type;
		}
		else{
			return false;
		}
	}

	protected function nearby_property($id = "")
	{
		if($id != "")
		{
			$property = DB::table($this->table)->where("id", "=", $id)->first();

			$select = "property.*, (
				6371 * acos (
			      cos ( radians(' .$property->longitude. ') )
			      * cos( radians( property.longitude ) )
			      * cos( radians( property.latitude ) - radians(' . $property->latitude. ') )
			      + sin ( radians(' . $property->longitude. ') )
			      * sin( radians( property.longitude ) )
			    )
			  ) AS distance";

			$nearby = $this->selectRaw($select)
				->where("property.deleted", 0)
				->where("property.id", "!=", $id)
				->orderBy("distance", 'asc')
				->limit(8)->get();


			return $nearby;

		}
		else{
			return false;
		}
	}

	protected function my_total_properties($user="", $filters=array())
	{
		$result = DB::table('property as PR');
		if($user != "")
		{
			$result = $result->where("user_id", $user);
		}
		if(sizeof($filters) > 0)
		{
			foreach($filters as $filter)
			{
				$result = $result->where($filter[0], $filter[1]);
			}
		}

		$result = $result->count();

		return $result;
	}

	protected function get_MyProperty($id, $offset, $limit, $filters=array())
	{
		$result = DB::table("property as PR");
		$result = $result->select("PR.*", "PT.property_type_name as property_type")
			->leftjoin("property_types as PT", "PT.id", "=", "PR.property_type");

		$result = $result->where("user_id", $id);
		if(sizeof($filters) > 0)
		{
			foreach($filters as $filter)
			{
				$result = $result->where($filter[0], $filter[1]);
			}
		}

		$result = $result->limit($limit)->offset($offset);
		$result = $result->orderby("PR.id", "DESC");
		$result = $result->get();

		if($result !=NULL)
		{
			$result = json_decode(json_encode($result),true);

			foreach($result as $ind => $property)
			{
				$filter = array(
					"post_id" => $property['id'],
					"post_type" => "property-image"
				);
				$table = new UploadModel();

				$images  = CI_ModelController::get_single($table, $filter);
				$result[$ind]['filename'] = $images['filename'];
			}

			return $result;
		}
		else
		{
			return false;
		}
	}

	protected function get_property($filters = array(), $limit = 1, $offset = 0,$property_id='')
	{
		$property = DB::table($this->table)->where("id", "=", $property_id)->first();

		$select = "PR.*,LU.filename,PT.property_type_name as property_type,PR.id, PR.direccion, PR.price, PR.property_deal, PR.rooms, PR.bathrooms, PR.sizem2, 6371*111.1111 * DEGREES(ACOS( COS(RADIANS(" . $property->longitude. ")) * COS(RADIANS(PR.longitude)) * COS(
		RADIANS(" . $property->latitude. " - PR.latitude)) + 
		SIN(RADIANS(" . $property->longitude. ")) * SIN(RADIANS(PR.longitude)) ) ) AS distance, LU.filename";

		$result = DB::table("property as PR");
		$result = $result->select(DB::raw($select))
						->leftjoin("property_types as PT", "PT.id", "=", "PR.property_type")
						->leftjoin("loquare_uploads as LU", "LU.id","=",DB::raw('(SELECT MIN(id) as lid FROM loquare_uploads as LU2 where LU2.post_type = "property-image" AND LU2.post_id = PR.id LIMIT 1)'));

		if(sizeof($filters) > 0)
		{
			foreach($filters as $filter)
			{
				$result = $result->where($filter[0], $filter[1], $filter[2]);
			}
		}
		$result = $result->limit($limit)->offset($offset);
		$result = $result->orderby("PR.id", "DESC");
		$result = $result->havingRaw("distance <10000");
		$result = $result->get();
		
		if($result !=NULL)
		{
			return json_decode(json_encode($result),true);
		}
		else
		{
			return false;
		}
	}
	
	protected function properties($filters = array())
	{
		$result = DB::table("property as PR");
		$result = $result->select(
				"PR.*", "LU.filename",
				"PT.property_type_name as property_type",
				"CO.company_name",
				"CO.company_email",
				"CO.company_phone",
				"CO.company_website",
				"CO.company_address",
				"admin_users.name as agent",
				"HO.hood", "PR.cops", "DT.dist_name", "PC.contact_name", "PC.contact_phone", "PC.contact_email"
			)
			->leftjoin("admin_users", "admin_users.id", "=", "PR.agent_id")
			->leftjoin("companies as CO", "CO.id", "=", "PR.company_id")
			->leftjoin("property_types as PT", "PT.id", "=", "PR.property_type")
			->leftjoin("hoods as HO", "HO.id", "=", "PR.hoods")
			->leftjoin("district as DT", "DT.id", "=", "PR.dist_id")
			->leftjoin("property_contact as PC", "PC.property", "=", "PR.id")
			->leftjoin("loquare_uploads as LU", "LU.id","=",DB::raw('(SELECT MIN(id) as lid FROM loquare_uploads as LU2 where LU2.post_type = "property-image" AND LU2.post_id = PR.id LIMIT 1)'));

		if(sizeof($filters) > 0)
		{
			foreach($filters as $filter)
			{
				$result = $result->where($filter[0], $filter[1]);
			}
		}

		$result = $result->paginate(10);

		if($result !=NULL)
		{
			return $result;
		}
		else
		{
			return false;
		}
	}

	public function district()
	{
		return $this->belongsTo('App\DistrictModel','dist_id');
	}

	public function hood()
	{
		return $this->belongsTo('App\HoodsModel','hoods');
	}

    public function agent()
    {
        return $this->belongsTo('App\AdminUser','agent_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

    public function images()
    {
        return $this->hasMany('App\UploadModel','post_id')->where("post_type", "property-image");
    }

    public function image()
    {
        return $this->hasOne('App\UploadModel','post_id')->where("post_type", "property-image");
    }

    public function offers()
    {
        return $this->hasMany('App\Offer', 'asset_id');
    }

    public function owner_certificate()
    {
        return $this->hasOne('App\UploadModel', 'post_id')->where('post_type', 'owner-certificate');
    }

    public function energy_certificate()
    {
        return $this->hasOne('App\UploadModel', 'post_id')->where('post_type', 'energy-certificate');
    }

    public function property_contact()
    {
        return $this->hasOne('App\properties\ContactModel', 'property');
    }

    public function price_history()
    {
    	return $this->hasMany('App\properties\PriceHistoryModel', 'property');
    }

    public function schedules()
    {
    	return $this->hasMany('App\SchedulsModel','property');
    }
}
