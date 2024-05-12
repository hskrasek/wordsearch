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
    public function up(): void
    {
        Schema::table('words', function (Blueprint $table) {
            $table->bigInteger('frequency')->nullable()->after('text');

            $table->index(['frequency', 'length']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('words', function (Blueprint $table) {
            $table->dropIndex(['frequency', 'length']);

            $table->dropColumn('frequency');
        });
    }
};
