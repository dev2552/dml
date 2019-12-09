<?php

namespace App\Models;

use App\Models\DomainModel;
use App\Models\IpModel;
use App\Models\ServerModel;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubDomainModel extends Model
{

    
    protected $table = 'sub_domain';

    protected $dates = ['created'];

    protected $fillable = ['ip_id','subdomain','domain_id','is_active','created'];

    public $timestamps = false;

    public function user()
    {
    	return $this->belongsTo(User::class,'user_id');
    }

    public function ip()
    {
        return $this->belongsTo(IpModel::class);
    }

    public function domain()
    {
        return $this->belongsTo(DomainModel::class);
    }
}
