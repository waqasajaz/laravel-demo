<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class CompanyModel extends Model
{
    protected $table = "companies";

	protected $fillable = [
		"company_name",
		"company_email",
		"company_phone",
		"company_website",
		"company_address",
		"userid"
	];

	protected static function companies($filters = "")
	{
		$data = DB::table("companies")->select(DB::raw("companies.*, COUNT(property.company_id) as total, admin_users.name as agent"))
			->join("property", "property.company_id", "=", "companies.id")
			->leftjoin("admin_users", "admin_users.id", "=", "companies.agent_id");

		if($filters != "")
		{
			foreach($filters as $filter)
			{
				$data = $data->where($filter[0], $filter[1]);
			}
		}

		$data = $data->orderBy("id", "DESC")
		->groupBy("property.company_id")
		->paginate(10);

		if($data != NULL)
		{
			return $data;
		}
		else{
			return FALSE;
		}
	}

}
