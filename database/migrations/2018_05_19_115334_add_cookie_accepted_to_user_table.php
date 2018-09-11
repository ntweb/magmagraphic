<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCookieAcceptedToUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dateTime('newsletter_accepted_at')->nullable()->after('active');
            $table->dateTime('cookie_5_accepted_at')->nullable()->after('active');
            $table->dateTime('cookie_4_accepted_at')->nullable()->after('active');
            $table->dateTime('cookie_3_accepted_at')->nullable()->after('active');
            $table->dateTime('cookie_2_accepted_at')->nullable()->after('active');
            $table->dateTime('cookie_1_accepted_at')->nullable()->after('active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('newsletter_accepted_at');
            $table->dropColumn('cookie_5_accepted_at');
            $table->dropColumn('cookie_4_accepted_at');
            $table->dropColumn('cookie_3_accepted_at');
            $table->dropColumn('cookie_2_accepted_at');
            $table->dropColumn('cookie_1_accepted_at');
        });
    }
}
