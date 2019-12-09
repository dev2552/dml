<?php

namespace App\Models;

use App\Models\GroupModel;
use App\Models\PaymentModel;
use App\Models\RegistrarModel;
use App\Models\IpModel;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class DomainModel extends Model
{


    protected $softCascade = ['payments'];

    protected $table = 'domain';

    public $timestamps = false;

    protected $fillable=['registrar_id','domain','group_id','datec','datex','status','description','is_active','is_expired','created_by','updated_by','deleted_by','entered','modified','ip_id','server_id','prod_status'];

    protected $dates = ['datec','datex','entered'];
    
    public function group()
    {
    	return $this->belongsTo(GroupModel::class);
    }

    public function registrar()
    {
    	return $this->belongsTo(RegistrarModel::class);
    }

    public function server()
    {
        return $this->belongsTo(ServerModel::class);
    }

     public function payments()
    {
        return $this->hasMany(PaymentModel::class,'domain_id');
    }

    public function getName()
    {
        return $this->domain;
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

    public function ip()
    {
        return $this->belongsTo(IpModel::class);
    }

    
}
