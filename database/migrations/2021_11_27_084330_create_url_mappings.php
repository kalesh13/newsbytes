<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUrlMappings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('url_mappings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('key')->nullable();
            $table->text('original_url');
            $table->boolean('access_only_once')->default(false);
            $table->unsignedBigInteger('opens')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        // For Sqlite auto increment. Insert a dummy data
        DB::insert('INSERT INTO url_mappings ("id", "original_url") values ("12345", "www.google.com")');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('url_mappings');
    }
}
