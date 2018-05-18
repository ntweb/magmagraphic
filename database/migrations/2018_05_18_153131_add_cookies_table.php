<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCookiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lab_cookies', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('active', [0,1]);
            $table->integer('id_created_by')->nullable();
            $table->integer('id_updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('lab_cookies_translations', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('cookie_id')->unsigned();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('locale')->index();

            $table->unique(['cookie_id','locale']);
            $table->foreign('cookie_id')->references('id')->on('lab_cookies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lab_cookies_translations');
        Schema::dropIfExists('lab_cookies');
    }
}
