<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('providers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable()->default('-');
            $table->string('country')->nullable()->default('-');
            $table->string('urlSite')->nullable()->default('-');
            $table->string('cpanel')->nullable()->default('-');
            $table->string('login')->nullable()->default('-');
            $table->string('password')->nullable()->default('-');
            $table->string('idCustomer')->nullable()->default('-');
            $table->string('email')->nullable()->default('-');
            $table->string('firstName')->nullable()->default('-');
            $table->string('lastName')->nullable()->default('-');
            $table->string('status')->nullable()->default('-');
            $table->string('type')->nullable()->default('-');
            $table->text('note')->nullable();
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->integer('updated_by')->unsigned()->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->integer('deleted_by')->unsigned()->nullable();
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('providers');
    }
}
