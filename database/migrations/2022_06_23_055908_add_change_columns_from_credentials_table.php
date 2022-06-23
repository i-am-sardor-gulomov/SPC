<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChangeColumnsFromCredentialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('credentials', function (Blueprint $table) {
            $table->dropColumn(['login', 'password']);
            $table->string('front_login')->nullable();
            $table->string('front_password')->nullable();
            $table->string('back_login')->nullable();
            $table->string('back_password')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('credentials', function (Blueprint $table) {
            $table->dropColumn(['front_login', 'front_password', 'back_login', 'back_password', ]);
            $table->string('login');
            $table->string('password');
        });
    }
}
