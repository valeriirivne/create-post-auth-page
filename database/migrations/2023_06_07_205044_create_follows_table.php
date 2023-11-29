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
        Schema::create('follows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->unsignedBigInteger('followeduser');
            $table->foreign('followeduser')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follows');
    }
};


//php artisan make:migration create_follows_table
//foreignId('user_id'): This method is used to define a foreign key column named "user_id" in the table. The foreignId() method is a shorthand for defining an unsigned big integer column with the naming convention of "{related_table}_id".
// constrained(): This method specifies that the "user_id" column is a foreign key that references the "id" column of another table. By default, it assumes that the related table is the plural form of the column name ("users" in this case) and the referenced column is the primary key "id". This method adds a foreign key constraint to enforce referential integrity.

// So, the line $table->foreignId('user_id')->constrained(); combines the creation of a foreign key column "user_id" in the current table and the specification that it references the "id" column of the "users" table. It establishes a relationship between the current table and the "users" table based on this foreign key column.

//////////////

// php
// Copy code
// $table->foreignId('user_id')->constrained();
// This line creates a foreign key column called "user_id" in the "follows" table. The foreignId() method is a convenient way to define a foreign key column. The constrained() method indicates that the foreign key references the "id" column of the related table, which in this case is the "users" table. This establishes a relationship between the "follows" table and the "users" table based on the "user_id" column.

// php
// Copy code
// $table->unsignedBigInteger('followeduser');
// This line creates an unsigned big integer column called "followeduser" in the "follows" table. It represents the ID of the user being followed. The column is unsigned, meaning it only accepts positive numbers.

// php
// Copy code
// $table->foreign('followeduser')->references('id')->on('users');
// This line creates a foreign key constraint on the "followeduser" column in the "follows" table. It specifies that the "followeduser" column references the "id" column of the "users" table. This ensures referential integrity by enforcing that the value in the "followeduser" column must exist as a primary key in the "users" table. In other words, it establishes a relationship between the "follows" table and the "users" table, where the "followeduser" column references the primary key "id" column of the "users" table.

// To summarize, these lines of code define a foreign key relationship between the "follows" table and the "users" table. The "user_id" column references the "id" column of the "users" table, and the "followeduser" column references the "id" column of the "users" table as well. These relationships allow you to link users with their followers and establish associations between the two tables.