<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\collection\CollectionModel as collect;
use App\Http\Controllers\CI_ModelController as common;
use Session;
use Redirect;
use App\collection\GrouplistModel as collectlist;
use App\properties\PropertyModel as property_data;
use DB;
use Illuminate\Support\Facades\Storage;
use Loquare;
use App\PropertyInvitation;
use App\User;
use App\Http\Controllers\LogsController as activity;

class CollectionsController extends Controller
{
	protected $data, $view, $logedin;

	function __construct()
	{
		parent::__construct();
		session::regenerate();

		$this->middleware('auth');
		$this->data['title'] = "Loquare";
		$this->data['layout'] = "template";
		$this->data['page'] = "Loquare";
		$this->data['success'] = session('success');
		$this->data['error'] = session('error');
		$this->data['location'] = "";
		$this->data['scripts'] = false;

		$this->middleware(function ($request, $next) {
			$this->logedin = Auth::user();
			return $next($request);
		});

	}

	public function load_view()
	{
		return view($this->view, $this->data);
	}

	function search_add_collection(Request $request)
	{
		$collection = $request->input("collection");
		$property   = $request->input("property");

		if(trim($collection) != "")
		{
			$table = new collect();
			$filter = array(
				array("collection", "like", "%".$collection."%"),
				array("user_id", "=", Auth::user()->id),
			);

			$data = common::get_by_condition($table, $filter);

			if($data == false)
			{
				$insert = array(
					"collection"    => $collection,
					"user_id"       => Auth::user()->id,
					"created_at"    => date('Y-m-d H:i:s')
				);

				$flag = common::insert_data($table, $insert);

				if($flag != false)
				{
					$data = common::get_by_condition($table, $filter);
				}

			}

			if($data != false)
			{
				$collections = '<input type="hidden" value="'.$property.'" name="property">';
				foreach($data as $collect)
				{
					$collections .= '<div class="list-checks__item active">
		                                <input type="radio" id="collect'.$collect['id'].'" name="collect" value="'.$collect['id'].'">
		                                <label for="collect'.$collect['id'].'">'.$collect['collection'].'<span class="list-checks__count">('.$collect['total_property'].')</span></label>
		                            </div>';
				}
				echo json_encode($collections);
			}
			else{
				echo json_encode(FALSE);
			}

		}
		else{
			return json_encode(FALSE);
		}
	}

	function savein_collection(Request $request)
	{

		$collection = $request->input("collect");
		$comment    = $request->input("collection_comment");
		$properties   = $request->input("property");

		$table = new collect();
		$filter = array(
			"user_id" => Auth::user()->id,
			"id" => $collection
		);
		$collection = common::get_single($table, $filter);

		if($properties != "" && $collection != false)
		{
			$table = new collectlist();
			$properties = explode(",", $properties);

			/*
			$table->whereNotIn("property_id", $properties)
			->where("user_id", Auth::user()->id)
			->where("collection_id", $collection['id'])->delete();
			*/

			foreach($properties as $property)
			{
				if(!is_numeric($property)){ continue; }
				$filter = array(
					"collection_id" => $collection['id'],
					"property_id"   => $property
				);

				$isexists = common::get_single($table, $filter);
				if(!$isexists)
				{
					$insert     = array(
						"property_id"   => $property,
						"collection_id" => $collection['id'],
						"property_from" => "retailer",
						"comment"       => $comment,
						"user_id"       => Auth::user()->id,
						"created_at"    => date('Y-m-d h:i:s')
					);

					$flag  = common::insert_data($table, $insert);

					if($flag != false)
					{
						Session::flash("success", "Congratulation!<br/>Property successfuly saved in \"".$collection['collection']."\"");
					}
					else{
						Session::flash("error", "Sorry! Something went wrong<br/>Please try again!");
					}
				}
				else{
					Session::flash("success", "Congratulation!<br/>Property successfuly saved in \"".$collection['collection']."\"");
				}
			}

			$totalincollection = $table->where([
				'collection_id' => $collection['id'],
				'user_id'       => Auth::user()->id
			])->count();

			$total_property = collect::find($collection['id']);
			$total_property->total_property = $totalincollection;
			$total_property->save();

		}
		else{
			Session::flash("error", "Sorry! Something went wrong<br/>Please try again!");
		}


		return redirect()->back();
	}

	public function collection($name = "", $collection_id="")
	{
		if($name != "")
		{
			if($name == $this->logedin->name)
			{
				$table = new collect();

				$filter = array(
					array("user_id", "=", $this->logedin->id),
					array("id", "=", $collection_id),
				);
				$exists = common::get_single($table, $filter);

				$this->data['scripts'] = array(
					asset("/frontend/js/collections.js")
				);

				$filter = array(
					array ("user_id", "=", $this->logedin->id)
				);
				$collections = common::get_by_condition($table, $filter);
				$this->data['collections'] = $collections;
				$this->data['collect_id'] = ($exists != false)?$exists['id']:$collections[0]['id'];

				$log = array(
					"type" => "page",
					"message" => "Visited My Collection page"
				);
				activity::addlog($log);

				$this->view  = "user.collections";
				return $this->load_view();
			}
			else{
				return Redirect("/");
			}
		}
		else{
			return Redirect("/");
		}
	}

	public function get_collection_property(Request $request)
	{
		$collection  = $request->input("collection");
		$limit       = $request->input("limit");
		$page        = $request->input("page");

		$offset = ($page - 1)*$limit;

		if($page == 1)
		{
			$collect = common::get_single(new collect(), array("id" => $collection));
			$log = array(
				"type" => "collection",
				"message" => "View collection - ".$collect['collection']
			);
			activity::addlog($log);
		}

		$data['collections'] = collect::get_collection_property($collection, $offset, $limit);
		foreach($data['collections'] as $key=>$value){
			$imagestore = asset('/storage/Property/'.$value['id'].'/thumbs/'.$value['filename']);

			$data['collections'][$key]['filename'] = Storage::disk('s3')->exists("Properties/".$value['id']."/".$value['filename'])?Storage::disk('s3')->url("Properties/".$value['id']."/".$value['filename']):$imagestore;
            $data['collections'][$key]['invited_users'] = collectlist::where('reference_id', $collection)->where('property_id',$value['pid'])->where('property_from', 'invite')->where('comment', 'invite')->where('invited_by',Auth::user()->id)->get();
		}
		$data['page'] = $page;

        $table = new collect();
        $filter = array(
        	array ("user_id", "=", $this->logedin->id)
        );
        $user_collections = common::get_by_condition($table, $filter);
        $data['user_collections'] = $user_collections;
        return view("user.collection_page", $data);
	}

	public function rename(Request $request)
	{
		$name = $request->input("name");
		$id = $request->input("collection");

		$collection = collect::find($id);

		if($collection->user_id = Auth::user()->id)
		{
			$oldname = $collection->collection;
			$collection->collection = $name;

			$flag = $collection->save();

			if($flag)
			{
				$log = array(
					"type" => "rename-collection",
					"message" => "Rename Collection ".$oldname." to ".$name
				);
				activity::addlog($log);
				echo json_encode(TRUE);
			}

		}
		else
		{
			echo json_encode(FALSE);
		}

		exit();
	}

	public function delete(Request $request)
	{
		$id = $request->input("collection");

		$collection = collect::where("id", $id)->where("user_id", Auth::user()->id)->first();
		$collectionname = $collection->collection;
		$flag = $collection->delete();
		
		if($flag)
		{
			collectlist::where("collection_id", $id)->where("user_id", Auth::user()->id)->delete();

			$log = array(
				"type" => "delete-collection",
				"message" => "Delete Collection ".$collectionname
			);
			activity::addlog($log);

			echo json_encode(
				array(
					"status" => 200,
					"message" => "Congratulation! <br/> Collection deleted successfuly!"
				)
			);
		}
		else
		{
			echo json_encode(
				array(
					"status" => 500,
					"message" => "Sorry! Something went wrong<br/>Please try again!"
				)
			);
		}
		exit();
	}

	function remove_from_collection(Request $request)
	{
		$collection = $request->input("collection");
		$property   = $request->input("property");

		$flag = collectlist::where("collection_id", $collection)
			->where("property_id", $property)
			->where("user_id", Auth::user()->id)
			->delete();

		if($flag != false || $flag != false)
		{
			$collection  = collect::find($collection);
			$collection->total_property = $collection->total_property - 1;
			$collection->save();

			$property = common::get_single(new property_data(), array("id" => $property));
			$log = array(
				"type" => "remove-collection-property",
				"message" => "Remove property ".$property['direccion']." from ".$collection->collection
			);
			activity::addlog($log);

			Session::flash("success", "Successful!<br/> property Removed from your collection!");

			echo json_encode(
				array(
					"status" => 200,
					"message" => "Congratulation! <br/> property removed from collection successfuly!"
				)
			);

		}
		else
		{
			Session::flash("error", "Sorry! Something went wrong<br/>Please try again!");
			echo json_encode(
				array(
					"status" => 500,
					"message" => "Sorry! Something went wrong<br/>Please try again!"
				)
			);
		}

		exit();
	}

	function update_collection_comment(Request $request)
	{
		$collection = $request->input("collection");
		$property = $request->input("property");
		$comment = $request->input("comment");

		$table = new collectlist();
		$filter = array(
			"collection_id" => $collection,
			"property_id" =>  $property,
			"user_id" => Auth::user()->id
		);

		$collection_property = common::get_single($table, $filter);

		if($collection_property != false)
		{
			$collection_property = collectlist::find($collection_property['id']);
			$collection_property->comment = trim($comment);
			$flag = $collection_property->save();

			if($flag) {

				$collection  = collect::find($collection);
				$property = common::get_single(new property_data(), array("id" => $property));
				$log = array(
					"type" => "collection-property-comment",
					"message" => "Update comment for property ".$property['direccion']." in collection ".$collection->collection." : '".$comment."'"
				);
				activity::addlog($log);

				echo json_encode(
					array(
						"status" => 200,
						"message" => "Congratulation! <br/> Comment updated successfuly!"
					)
				);
			}
			else {
				echo json_encode(
					array(
						"status" => 500,
						"message" => "Sorry! Something went wrong<br/>Please try again!"
					)
				);
			}
		}
		else
		{
			echo json_encode(
				array(
					"status" => 500,
					"message" => "Sorry! Something went wrong<br/>Please try again!"
				)
			);
		}

		exit();
	}
	function get_collection($type="post", $id = "", Request $request)
	{
		if($id == "")
		{
			$id = $request->input("id");
		}

		$filter = array(
			array("post_id" , $id)
		);

		$data = collect::get_collection($filter);

		if($type = "json")
		{
			echo json_encode($data);
		}
		else{
			return $data;
		}
		exit();
	}
	
	function get_collection_data(Request $request)
	{
		$filter= array('id'=>$request->input('id'));

		$table = new property_data();
		$data = common::get_single($table, $filter);

		if($data != false)
		 {
			if($type = "json")
			{
				echo json_encode($data);
			}
			else
			{
				return $data;
			}

		}
		exit();
	}

    function switchCollection(Request $request)
    {
        $exist = collectlist::where('property_id', $request->propertyid)->where('user_id', Auth::user()->id)->where('collection_id', $request->collectionid)->first();
        if($exist) {
            return redirect('collections/'.Auth::user()->name.'/'.$request->current_collection_id)->with('success', "Property already exist in the selected collection");
        }

        $user_property_collection = collectlist::where('property_id', $request->propertyid)->where('user_id', Auth::user()->id)->first();
        if($user_property_collection && $request->collectionid > 0) {
            $old_collection = collect::find($user_property_collection->collection_id);
            $new_collection = collect::find($request->collectionid);

            if($new_collection) {
                $user_property_collection->collection_id = $request->collectionid;
                $user_property_collection->save();

                $new_collection->total_property = $new_collection->total_property + 1;
                $new_collection->save();

                $old_collection->total_property = $old_collection->total_property - 1;
                $old_collection->save();

	            $property =  property_data::find($request->propertyid);

	            $log = array(
	            	"type" => "change-property-collection",
		            "message" => "Move property ".$property->direccion." from collection ".$old_collection->collection." to ".$new_collection->collection
	            );
	            activity::addlog($log);

                return redirect('collections/'.Auth::user()->name.'/'.$request->current_collection_id)->with('success', "Property switched successfully");
            }  else {
                return redirect('collections/'.Auth::user()->name.'/'.$request->current_collection_id)->with('error', "Collection does not exist.");
            }
        } else {
            return redirect('collections/'.Auth::user()->name.'/'.$request->current_collection_id)->with('error', "Something went wrong. Please try again later.");
        }
    }

    function wishlistarray()
    {
    	$user = Auth::user()->id;
	    $filter = array("user_id" => $user);
	    $table = new collectlist();
	    $data = $table->select(DB::raw("GROUP_CONCAT(property_id) as wishlist"))->get();

	    if($data)
	    {
	    	echo json_encode(explode(",",$data[0]->wishlist));
	    }
	    else{
			echo json_encode(FALSE);
	    }
    }
}
