<?php

namespace App;

use App\Models\GroupModel;
use App\Models\RequestModel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\RegistrarModel;
use App\Models\DomainModel;
use App\Models\ServerModel;
use App\Models\ProviderModel;
use App\Models\IpModel;
use App\Models\PaymentModel;
use App\Models\RoleModel;


class User extends Authenticatable
{

     use Notifiable;

    protected $softCascade = ['requests'];

    protected $primaryKey = 'username';

    public $incrementing = false;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'active', 'created','email','f_name','flag','l_name','modified','password','type','group_id','roule_id','id_grp','created_by','updated_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function role()
    {
        return $this->belongsTo(RoleModel::class,'roule_id');
    }
   

    public function group(){
        return $this->belongsTo(GroupModel::class);
    }

   

    public function isRoot()
    {
        return $this->roule_id == 'root';
    }

    public function isManager()
    {
        return $this->roule_id == 'manager';
    }

    public function isUser()
    {
        return $this->roule_id == 'user';
    }

     public function requests()
    {
        return $this->hasMany(RequestModel::class,'user_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class,'updated_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class,'deleted_by');
    }

    public function registrar()
    {
        return $this->hasOne(RegistrarModel::class);
    }

    public function domain()
    {
        return $this->hasOne(DomainModel::class);
    }

    public function server()
    {
        return $this->hasOne(ServerModel::class);
    }

    public function provider()
    {
        return $this->hasOne(ProviderModel::class);
    }

    public function ip()
    {
        return $this->hasOne(IpModel::class);
    }

    public function payment()
    {
        return $this->hasOne(PaymentModel::class);
    }

     /**
   * Overrides the method to ignore the remember token.
   */
  public function setAttribute($key, $value)
  {
    $isRememberTokenAttribute = $key == $this->getRememberTokenName();
    if (!$isRememberTokenAttribute)
    {
      parent::setAttribute($key, $value);
    }
  }

   
}
