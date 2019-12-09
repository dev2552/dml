<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistrarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registrars', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable()->default('-');
            $table->string('company')->nullable()->default('-');
            $table->string('email')->nullable()->default('-');
            $table->string('password')->nullable()->default('-');
            $table->string('customerId')->nullable()->default('-');
            $table->string('key')->nullable()->default('-');
            $table->string('secret')->nullable()->default('-');
            $table->string('website')->nullable()->default('-');
            $table->string('phone')->nullable()->default('-');
            $table->string('address')->nullable()->default('-');
            $table->text('description')->nullable();
            $table->boolean('enable')->nullable()->default(true);
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
        Schema::dropIfExists('registrars');
    }
}
