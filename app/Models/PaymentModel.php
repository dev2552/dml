<?php

namespace App\Models;

use App\Models\DomainModel;
use App\Models\GroupModel;
use App\Models\IpModel;
use App\Models\ServerModel;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class PaymentModel extends Model
{


    protected $table = 'payment';

    protected $fillable = ['type','group_id','unit_price','quantity','total_price','currency','payment_date','description','createDate','next_payment','status','server_id'/*,'domain_id'/*,'ip_id'*/,'created_by','updated_by','deleted_by','deleted_at','created'];

    protected $dates = ['created','payment_date','next_payment'];

    public $timestamps = false;


    public function group(){

    	return $this->belongsTo(GroupModel::class);
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

    public function server()
    {
        return $this->belongsTo(ServerModel::class);
    }

    public function domain()
    {
        return $this->belongsTo(DomainModel::class);
    }

    public function ip()
    {
        return $this->belongsTo(IpModel::class);
    }

   
}
