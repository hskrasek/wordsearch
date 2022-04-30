<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        \Illuminate\Support\Facades\DB::statement(
            'CREATE INDEX CONCURRENTLY words_text_trgm_index ON words USING gin (lower(text) gin_trgm_ops);'
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Illuminate\Support\Facades\DB::statement(
            'DROP INDEX CONCURRENTLY IF EXISTS words_text_trgm_index'
        );
    }
};
