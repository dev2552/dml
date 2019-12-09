<?php

namespace App\Models;

use App\Models\PaymentModel;
use App\Models\ProviderModel;
use App\Models\ServerModel;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Geoplugin;



class IpModel extends Model
{


    protected $softCascade = ['payments'];

    protected $table = 'ip';

    protected $fillable = [
        'server_id',
        'ip',
        'ip_range',
        'netmask',
        'price',
        'currency',
        'type',
        'is_active',
        'created',
        'provider',
        //'ipCountry',
       // 'ipCountryCode',
        'ip_status',
        'gateway',
        //'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at'];

    protected $dates = ['created'];

    public $timestamps = false;

    public function group(){
    	return $this->server->group();
    }

    public function provider(){
    	return $this->belongsTo(ProviderModel::class);
    }

    public function server(){
    	return $this->belongsTo(ServerModel::class);
    }

    public function payments()
    {
        return $this->hasMany(PaymentModel::class,'ip_id');
    }

    public function getName()
    {
        return $this->ip;
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
