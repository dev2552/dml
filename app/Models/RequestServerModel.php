<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class RequestServerModel extends Model
{



    protected $table = 'request_server';

    protected $fillable = ['server','subject'];


}
