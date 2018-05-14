<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Input;
use Redirect;
use Excel;
use App\Offer;
use App\AdminUser;
use App\properties\PropertyModel as property;
use Crypt;

class AgentsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth.admin');
        $this->offer = new Offer();
        $this->objAdminUser = new AdminUser();
    }

    public function index()
    {
        if(Auth::guard('admin')->user()->role->type != 'admin') {
            return redirect('/admin/dashboard');
        }
        $agents = AdminUser::where('role_id', 2)->get();

        return view('admin.agents', compact('agents'));
    }

    public function create()
    {
        return view('admin.EditAgent');
    }

    public function save(Request $request)
    {
        $requestData = $request->all();

        if(isset($requestData['id']) && $requestData['id'] > 0) {
            $this->validate($request,[
                'name'=>'required',
                'email'=>'required|email|unique:admin_users,email,' . $request->get('id'),
            ]);
        } else {
            $this->validate($request,[
                'name'=>'required',
                'email'=>'required|email|unique:admin_users,email'
            ]);
        }


        if(isset($requestData['password']) && $requestData['password'] != '') {
            $requestData['password'] = bcrypt($requestData['password']);
        } else {
            unset($requestData['password']);
        }

        $response = $this->objAdminUser->insertUpdate($requestData);
        if ($response) {
            return Redirect::to("/admin/agent")->with('success', 'Agent created successfully');
        } else {
            return Redirect::to("/admin/agent")->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function edit($id)
    {
        $data = AdminUser::find(Crypt::decrypt($id));
        if($data) {
            return view('admin.EditAgent', compact('data'));
        } else {
            return Redirect::to("/admin/agent")->with('error', 'Agent not exist');
        }
    }

    public function delete($id)
    {
        $id = Crypt::decrypt($id);
        $user = AdminUser::find($id);
        $user->delete();

        return Redirect::to("/admin/agent")->with('success', 'Agent deleted successfully');
    }
}
