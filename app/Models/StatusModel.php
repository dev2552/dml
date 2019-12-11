<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;


class StatusModel extends Model
{


    protected $table = 'status';

    protected $fillable = ['status','explication','request_id','server_id','created_by','created','deleted_at'];

    protected $dates = ['created'];

    public $timestamps = false;

    const CREATED_AT = "created";

   public function server()
   {
    return $this->belongsTo(ServerModel::class);
   }

   public function request()
   {
    return $this->belongsTo(RequestModel::class);
   }

   

    public function createdBy()
    {
    	return $this->belongsTo(User::class,'created_by');
    }

   
  

    


}
