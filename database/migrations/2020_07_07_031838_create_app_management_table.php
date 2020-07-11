<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAppManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_management', function (Blueprint $table) {
            $table->id();
            $table->string('base_url')->nullable();
            $table->string('logo')->nullable();
            $table->timestamps();
        });

        DB::table('app_management')->insert([
            'base_url' => null,
            'logo' => null,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_management');
    }
}
