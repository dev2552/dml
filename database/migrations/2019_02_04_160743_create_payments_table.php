<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->nullable()->default('-');
            $table->integer('paymentable_id')->nullable();  // servers - ips - domains
            $table->string('paymentable_type')->nullable();
            $table->unsignedInteger('group_id');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->integer('unitPrice')->nullable()->default(0);
            $table->integer('quantity')->nullable()->default(0);
            $table->double('totalPrice')->nullable()->default(0);
            $table->string('currency')->nullable()->default('-');
            $table->date('paymentDate')->nullable()->default(now());
            $table->text('description')->nullable();
            $table->date('createDate')->nullable()->default(now());
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
        Schema::dropIfExists('payments');
    }
}
