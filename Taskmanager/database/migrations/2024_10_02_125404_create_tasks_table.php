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
    // database/migrations/xxxx_xx_xx_create_tasks_table.php
     public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('description')->nullable();
    $table->boolean('completed')->default(false);
    $table->foreignId('assigned_to')->constrained('users')->onDelete('cascade');
    $table->foreignId('created_by')->constrained('users','user_id')->onDelete('cascade');
    $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');  // New 'updated_by' column
    $table->softDeletes();
    $table->timestamps();
});

    }

    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }

    
    

};
