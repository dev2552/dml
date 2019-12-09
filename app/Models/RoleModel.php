<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class RoleModel extends Model 
{
	protected $table = "role";

	protected $fillable = [ "role","description" ];

	public $timestamps = false;

	protected $primaryKey = "role";

	 public $incrementing = false;

	public function users(){
		return $this->hasMany(User::class,'roule_id');
	}
}