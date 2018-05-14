<?php

namespace App\collection;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CI_ModelController;
use App\UploadModel;
use DB;
use App\collection\GrouplistModel;

class CollectionModel extends Model
{
	protected $table = "collections";

	protected $fillable = array('collection', 'user_id', 'total_property');


	static function get_collection_property($collection = "", $offset, $limit)
	{

		if($collection != "")
		{
			$data = DB::table("property as PR");
			$data = $data->select("CL.property_id AS pid", "PR.*", "CL.id as cid", "CL.comment", "PT.property_type_name as property_type")
				->leftjoin("property_in_collections as CL", "CL.property_id", "=", "PR.id")
				->leftjoin("property_types as PT", "PT.id", "=", "PR.property_type")
				->where("CL.collection_id", "=", $collection)
				->limit($limit)
				->offset($offset)
				->get();

			if($data != NULL)
			{
				$result = json_decode(json_encode($data ),true);

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
			else{
				return false;
			}

		}
		else{
			return false;
		}

	}

	static function get_collection($filters = array())
	{
		$result = DB::table("loquare_uploads");
		if(sizeof($filters) > 0)
		{
			foreach($filters as $filter)
			{
				$result = $result->where($filter[0], $filter[1]);
			}
		}
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

    public function user_collection_properties()
    {
        return $this->hasMany('App\collection\GrouplistModel', 'collection_id')->where('user_id', Auth::user()->id);
    }
}
