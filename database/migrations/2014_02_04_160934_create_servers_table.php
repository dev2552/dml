<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('group_id');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->unsignedInteger('provider_id');
            $table->foreign('provider_id')->references('id')->on('providers')->onDelete('cascade');
            $table->string('name')->nullable()->default('-');
            $table->string('orderNumber')->nullable()->default('-');
            $table->string('mainIp')->nullable()->default('-');
            $table->unsignedInteger('domain_id');
            $table->foreign('domain_id')->references('id')->on('domains')->onDelete('cascade');
            $table->string('sshUserDefault')->nullable()->default('-');
            $table->string('sshType')->nullable()->default('-');
            $table->string('sshPasswordDefault')->nullable()->default('-');
            $table->string('key')->nullable()->default('-');
            $table->double('price')->nullable()->default(0);
            $table->string('currency')->nullable()->default('-');
            $table->date('dateOrder')->nullable()->default(now());
            $table->date('datePurchase')->nullable()->default(now());
            $table->date('dateExpiration')->nullable()->default(now());
            $table->date('dateCancelation')->nullable();
            $table->string('os')->nullable()->default('-');
            $table->string('cpu')->nullable()->default('-');
            $table->string('ram')->nullable()->default('-');
            $table->string('hdd')->nullable()->default('-');
            $table->string('bandWidth')->nullable()->default('-');
            $table->text('description')->nullable();
            $table->text('note')->nullable();
            $table->string('type')->nullable()->default('-');
            $table->date('dateDelivred')->nullable()->default(now());
            $table->string('mainIpCountry')->nullable()->default('-');
            $table->string('mainIpCountryCode')->nullable()->default('-');
            $table->string('_status')->nullable()->default('-');
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->integer('deleted_by')->unsigned()->nullable();
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
        Schema::dropIfExists('servers');
    }
}
