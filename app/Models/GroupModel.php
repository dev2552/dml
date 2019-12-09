<?php

namespace App\Models;


use App\Models\DomainModel;
use App\Models\IpModel;
use App\Models\PaymentModel;
use App\Models\RequestModel;
use App\Models\ServerModel;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class GroupModel extends Model
{


    protected $softCascade = ['servers','domains','payments','users'];

    protected $table = 'group_dml';

    protected $fillable = ['name','created'];
    protected $dates = ['created'];

    public $timestamps = false;

    public function domains()
    {
    	return $this->hasMany(DomainModel::class,'group_id');
    }

    public function payments()
    {
        return $this->hasMany(PaymentModel::class,'group_id');
    }

    public function requests()
    {
        return $this->hasManyThrough(RequestModel::class,User::class,'group_id','user_id','id','username');
    }

    public function servers()
    {
        return $this->hasMany(ServerModel::class,'group_id');
    }

    public function users()
    {
        return $this->hasMany(User::class,'group_id');
    }

    public function ips()
    {
        return $this->hasManyThrough(IpModel::class,ServerModel::class,'group_id','server_id');
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

   public function getUpdatedAtColumn() {
    return null;
}


}
