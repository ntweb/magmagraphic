<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNoindexToPageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lab_pages', function (Blueprint $table) {
            $table->enum('noindex', array(0, 1))->default(0)->after('module');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lab_pages', function (Blueprint $table) {
            $table->dropCOlumn('noindex');
        });
    }
}
