<?php

namespace App\Models;

use App\Models\DomainModel;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class RegistrarModel extends Model
{


    protected $softCascade = ['domains'];

    protected $table ='registrar';

    public $timestamps = false;

    protected $fillable = ['name','company','eml_contact','password','customerid','registrar_key','secret','website','phone','address','is_active','description','created_by','updated_by','deleted_by'];



    public function domains()
    {
    	return $this->hasMany(DomainModel::class,'registrar_id');
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
