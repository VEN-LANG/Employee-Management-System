<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if(Schema::hasTable('employees')) {
            Schema::table('employees', function (Blueprint $table) {
                // Remove the old position column
                $table->dropColumn('position');

                // Add the new position_id column
                $table->unsignedBigInteger('position_id')->after('phone_number');

                // Add foreign key constraint
                $table->foreign('position_id')->references('id')->on('positions')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(Schema::hasTable('employees')) {
            Schema::table('employees', function (Blueprint $table) {
                // Drop foreign key and position_id column
                $table->dropForeign(['position_id']);
                $table->dropColumn('position_id');

                // Re-add the old position column
                $table->string('position')->after('phone_number');
            });
        }
    }
};
