<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntelxDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intelx_data', function (Blueprint $table) {
            $table->id();
            $table->string('systemid');
            $table->string('storageid');
            $table->boolean('instore');
            $table->integer('type');
            $table->integer('media');
            $table->string('added');
            $table->string('name')->nullable();
            $table->string('bucket');
            $table->string('preview')->nullable();
            $table->string('data')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('domain_id')->nullable();
            $table->unsignedBigInteger('email_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('domain_id')
                ->references('id')
                ->on('domains')
                ->onDelete('cascade');

            $table->foreign('email_id')
                ->references('id')
                ->on('emails')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('intelx_data');
    }
}
