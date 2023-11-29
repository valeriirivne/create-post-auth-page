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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('isAdmin')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('isAdmin');
        });
    }
};


//php artisan make:migration add_isadmin_to_users_table --table=users
//php artisan migrate --path=/database/migrations/2023_06_06_204024_add_isadmin_to_users_table.php
