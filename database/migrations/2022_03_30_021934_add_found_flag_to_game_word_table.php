<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('game_word', function (Blueprint $table) {
            $table->boolean('found')->default(false);
            $table->timestamp('found_at')->nullable();

            $table->index(['found', 'found_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('game_word', function (Blueprint $table) {
            $table->dropIndex(['found', 'found_at']);

            $table->dropColumn(['found', 'found_at']);
        });
    }
};
