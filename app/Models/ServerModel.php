<?php

namespace App\Models;

use App\Models\DomainModel;
use App\Models\GroupModel;
use App\Models\IpModel;
use App\Models\PaymentModel;
use App\Models\ProviderModel;
use App\Models\StatusModel;
use App\Models\SubDomainModel;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Geoplugin;


class ServerModel extends Model
{


    protected $softCascade = ['ips','payments'];

    protected $table = 'server';

    protected $fillable = [
        'group_id',
        'provider_id',
        's_name',
        'order_number',
        'main_ip',
        'main_domain',
        'ssh_user_default',
        'ssh_password_default',
        'ssh_key',
        'price',
        'currency',
        'date_order',
        'date_purchase',
        'date_expiration',
        'date_cancelation',
        'os',
        'cpu',
        'ram',
        'hdd',
        'band_width',
        'description',
        'note','type_id',
        'date_delivred',
       // 'mainIpCountry',
       // 'mainIpCountryCode',
        //'created_by',
      //  'updated_by',
       // 'deleted_by',
       // 'last_status',
        'created',
        'type_id'];

    public $timestamps = false;

    protected $dates = ['date_order','date_purchase','date_expiration','date_cancelation','created','date_delivred'];

    public function group()
    {
    	return $this->belongsTo(GroupModel::class);
    }

    public function provider()
    {
    	return $this->belongsTo(ProviderModel::class);
    }

    public function domain()
    {
    	return $this->belongsTo(DomainModel::class);
    }

    public function status(){
        return $this->hasMany(StatusModel::class,'server_id');
    }

    public function ips()
    {
        return $this->hasMany(IpModel::class,'server_id');
    }

    public function payments()
    {
        return $this->hasMany(PaymentModel::class,'server_id');
    }

    public function getName()
    {
        return $this->name;
    }

    public function subDomains()
    {
        return $this->hasManyThrough(SubDomainModel::class,IpModel::class,'server_id','ip_id');
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
