<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Migration1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("registrar",function(Blueprint $table){
            $table->string("created_by")->nullable();
            $table->string("updated_by")->nullable();
        });

         Schema::table("domain",function(Blueprint $table){
            $table->string("created_by")->nullable();
            $table->string("updated_by")->nullable();
            $table->double("price")->nullable();
            $table->string("currency")->nullable();
            $table->boolean("alreadyAssigned")->default(0);
        });

          Schema::table("server",function(Blueprint $table){
            $table->string("created_by")->nullable();
            $table->string("updated_by")->nullable();
            $table->string("mainIpCountryCode")->nullable();
            $table->string("mainIpCountry")->nullable();
            //$table->string("last_status")->nullable();
        });

           Schema::table("provider",function(Blueprint $table){
            $table->string("created_by")->nullable();
            $table->string("updated_by")->nullable();
        });

           Schema::table("ip",function(Blueprint $table){
            $table->string("created_by")->nullable();
            $table->string("updated_by")->nullable();
            $table->string("ipCountry")->nullable();
            $table->string("ipCountryCode")->nullable();
            $table->foreign("server_id")->references("id")->on("servers")->onDelete("cascade");
        });

           Schema::table("request",function(Blueprint $table){
            $table->string("_status")->nullable();
        });

           Schema::table("payment",function(Blueprint $table){
                $table->Integer("domain_id")->nullable();
                $table->foreign("domain_id")->references("id")->on("domain")->onDelete("cascade");
                $table->Integer("ip_id")->nullable();
                $table->foreign("ip_id")->references("id")->on("ip")->onDelete("cascade");
                $table->string("created_by")->nullable();
                $table->string("updated_by")->nullable();
           });

           Schema::table("users",function(Blueprint $table){
            $table->string("created_by")->nullable();
            $table->string("updated_by")->nullable();
        });

           Schema::table("notifications",function(Blueprint $table){
                $table->string("notifiable_id",50)->index()->change();
           });

             Schema::table("sub_domain",function(Blueprint $table){
                $table->string("user_id")->nullable();

           });

              Schema::table("status",function(Blueprint $table){
                $table->string("created_by")->nullable();
           });

              Schema::table("group_dml",function(Blueprint $table){
                $table->string("created_by")->nullable();
                $table->string("updated_by")->nullable();
              });


        \DB::getPdo()->exec( "update request r set _status = ( select status from status where request_id=r.id order by id desc limit 1 )" );

        \DB::getPdo()->exec( "update server s set last_status = ( select status from status where server_id=s.id order by id desc limit 1 )" );

        \DB::table("users")->where("username","admin")->update(["password"=>bcrypt("secret")]);


    }
}


