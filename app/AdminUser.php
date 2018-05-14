<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Config;
use DB;

class AdminUser extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract {

    use Authenticatable,
        Authorizable,
        CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'admin_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'role_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function insertUpdate($data)
    {
          if (isset($data['id']) && $data['id'] != '' && $data['id'] > 0) {
              $updateData = [];
              foreach ($this->fillable as $field) {
                  if (array_key_exists($field, $data)) {
                      $updateData[$field] = $data[$field];
                  }
              }
              return AdminUser::where('id', $data['id'])->update($updateData);
          } else {
              return AdminUser::create($data);
          }
      }

    public function getActiveUserDetailByEmail($email) {
      $userDetail = $this->where(['email' => $email])->first();

      return $userDetail;
    }

    public function role()
    {
      return $this->belongsTo('App\Role');
    }
}
