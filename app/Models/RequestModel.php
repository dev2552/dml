<?php

namespace App\Models;

use App\Models\GroupModel;
use App\Models\StatusModel;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class RequestModel extends Model
{


    protected $table = 'request';

    protected $fillable = ['type','subject','request','priority','user_id','deleted_at','deleted_by','_status','created'];

    public $timestamps = null;

    public $dates = ["created"];

    public function group(){return $this->belongsTo(GroupModel::class);}


    public function user()
    {
    	return $this->belongsTo(User::class,'user_id','username');
    }

    public function status(){return $this->hasMany(StatusModel::class,'request_id');}

    public function lastStatus()
    {
        return $this->status()->orderBy("id","desc")->limit(1);
    }

    public function scopeFilterForRoot($query,$data)
    {
        return $query
        ->where( function( $query ) use ( $data ){ $query->where( "subject","like","%".$data[ "subject" ]."%" )->orWhereNull( "subject" ); } )
        ->where( function( $query ) use ( $data ){ $query->where( "type","like","%".$data[ "type" ]."%" )->orWhereNull( "type" ); } )
        ->where( function( $query ) use ( $data ){ $query->where( "priority","like","%".$data[ "priority" ]."%" )->orWhereNull( "priority" ); } )
        ->where( function( $query ) use ( $data ){ $query->where( "user_id","like","%".$data[ "user_id" ]."%" )->orWhereNull( "user_id" ); } )
        ->whereHas("lastStatus",function($q)use($data){
            $q->where("status","like","%".$data["_status"]."%");
        })
        ->orderBy($data['sortObject']['champ'],$data['sortObject']['order'])
        ->paginate($data["limit"]);
    }

    
}
