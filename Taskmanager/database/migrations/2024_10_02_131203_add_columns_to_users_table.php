<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes(); // Adds 'deleted_at' column for soft deletes
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null'); // User who last updated the record
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null'); // User who created the record
            $table->string('role')->default('user'); // Role column with a default value of 'user'
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
            $table->dropColumn('deleted_at');
            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
            $table->dropColumn('role');
        });
    }
}
