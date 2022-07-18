<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveColumnsAppsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('apps', function (Blueprint $table) {
            $table->dropColumn(['client_id', 'client_secret', 'grant_type', 'port', 'IP']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('apps', function (Blueprint $table) {
            $table->smallInteger('client_id');
            $table->string('client_secret');
            $table->string('grant_type');
            $table->string('IP');
            $table->string('port');
        });
    }
}
