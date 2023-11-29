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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title');
            $table->longText('body');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};


// php artisan make:migration create_posts_table
// php artisan migrate


// The migration file itself does not directly interact with the .env file. Instead, it interacts with the Laravel application through the Laravel migration system.

// When you run the migration using the Artisan command php artisan migrate, Laravel reads the migration files and executes the up() method defined in each migration file. The migration files define the structure of the database tables, relationships, and any other necessary modifications.

// Laravel uses the database configuration specified in the .env file to determine which database connection to use for the migration. The database configuration in the .env file includes details such as the database driver, host, port, database name, username, and password. Laravel uses this information to establish a connection to the database and execute the necessary SQL statements to create or modify the database schema based on the migration files.

// So, while the migration files are not directly interacting with the .env file, the .env file provides the necessary configuration for Laravel to connect to the database and execute the migrations. The migration system is an integral part of Laravel's database management and allows you to version and control the structure of your database within your application.