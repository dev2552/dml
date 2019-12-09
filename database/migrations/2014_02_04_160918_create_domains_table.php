<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('domains', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('registrar_id');
            $table->foreign('registrar_id')->references('id')->on('registrars')->onDelete('cascade');
            $table->string('domain')->nullable()->default('-');
            $table->unsignedInteger('group_id');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->date('datePurchase')->nullable()->default(now());
            $table->date('dateExpiration')->nullable()->default(now());
            $table->string('status')->nullable()->default('New');
            $table->text('description')->nullable();
            $table->boolean('enable')->nullable()->default(1);
            $table->boolean('expired')->nullable()->default(true);
            $table->double('price')->nullable()->default(0);
            $table->string('currency')->nullable()->default('-');
            $table->boolean('alreadyAssigned')->default(false);
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
        Schema::dropIfExists('domains');
    }
}
