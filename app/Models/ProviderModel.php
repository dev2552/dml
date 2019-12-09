<?php

namespace App\Models;

use App\Models\IpModel;
use App\Models\ServerModel;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ProviderModel extends Model
{


    protected $softCascade = ['servers'];

    protected $table = 'provider';

    public  $timestamps = false;

    protected $fillable = ['name','country','url_site','cpanel','login','pwd','id_customer','inscr_email','inscrfname','insclname','type','note','status','created_by','updated_by','deleted_by','deleted_at','created'];

    protected $dates = ['created'];

    public function ips()
    {
    	return $this->hasMany(IpModel::class,'provider_id');
    }

     public function servers()
    {
        return $this->hasMany(ServerModel::class,'provider_id');
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

 
}
