<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     * 
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];


    protected function avatar(): Attribute
    {
        return Attribute::make(get: function ($value) {
            return $value ? '/storage/avatars/' . $value : '/fallback-avatar.jpg';
        });
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function feedPosts()
    {
        return $this->hasManyThrough(Post::class, Follow::class, 'user_id', 'user_id', 'id', 'followeduser');
    }


    public function followers()
    {

        return $this->hasMany(Follow::class, 'followeduser');
    }

    public function followingTheseUsers()
    {
        // if we want the list what everyone val is following we just want the rows the value is match
        return $this->hasMany(Follow::class, 'user_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id');
    }
}

// In Laravel we don't write mySql code ourselves, we use something called Model, which make it really to perform database actions for us


// By using the use statements, you can import the necessary classes, traits, or contracts from various Laravel packages into your current file, making them accessible within that file. This helps to keep your code clean, readable, and reduces the need for fully qualified names.

// The imported elements are part of the Laravel framework and are provided by Laravel's dependencies. They are included in your project when you install Laravel or its packages through Composer. When you run the composer install or composer update command, Composer retrieves the required packages and installs them in your project's vendor directory. Laravel's autoloader then allows you to use the use statements to import the necessary classes and traits without explicitly specifying their full paths.

////

// In Laravel, the naming convention for database tables associated with models is based on the plural form of the model's class name. In the case of the User model, Laravel assumes that the corresponding database table is named users. This naming convention follows the principle of "convention over configuration" that Laravel promotes.

// !!!!!!!!!!!!!!!!!!!!!!!!!!!

// In the context of a web application, the user model represents the metaphorical concept of a user. It defines the attributes and behavior of a user within the application. The user model is responsible for interacting with the underlying database table that stores user information.

// To understand the metaphor, let's consider an analogy. In real life, a user can be seen as an individual who interacts with a system or service. For example, in an e-commerce application, a user represents a person who registers, logs in, and performs various actions such as browsing products, adding items to a cart, and making purchases.

// Similarly, in a web application, the user model represents this metaphorical concept of a user. It encapsulates the information and functionalities related to a user, such as their username, email address, password, and any additional attributes specific to the application's requirements.

// The user model allows you to define the structure and behavior of a user object within your application. It provides methods for authenticating users, managing their attributes, and performing actions related to the user, such as creating, updating, and deleting user records in the database. Additionally, it can define relationships with other models, such as a user having multiple posts or being associated with certain roles or permissions.

// By interacting with the user model, you can create instances of users, retrieve user data, update user information, and perform various operations related to user management. !!!!!!!!!!!!!!The user model acts as a bridge between the application's code and the database, allowing you to perform CRUD operations (Create, Read, Update, Delete) on user data.

// Overall, the user model represents the metaphorical concept of a user in your web application and provides a structured way to handle user-related operations and interactions.


// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

// The User model interacts with the database in several ways:

// Retrieving User Data: The User model allows you to retrieve user data from the database. For example, you can use the model's static methods like find(), where(), or all() to retrieve user records based on specific criteria. These methods generate SQL queries behind the scenes and return user objects representing the retrieved data.

// Creating New Users: You can create new user records in the database using the User model. By instantiating a new User object and setting the desired attributes, you can save the user data to the database using the save() method. This method executes an SQL INSERT query to create a new user record.

// Updating User Data: Once you have retrieved a user record, you can update its attributes and save the changes back to the database. By modifying the attributes of the User object and calling the save() method, an SQL UPDATE query is executed to update the corresponding user record in the database.

// Deleting User Records: The User model provides methods to delete user records from the database. You can call the delete() method on a User object to remove the corresponding record from the database. This executes an SQL DELETE query.

// Relationships with Other Models: The User model can define relationships with other models in the application. For example, the posts() method in the User model establishes a one-to-many relationship between users and posts. This allows you to retrieve a user's posts or associate posts with a specific user.

// Under the hood, Laravel's Eloquent ORM (Object-Relational Mapping) handles the database interactions for the User model. It abstracts away the complexities of SQL queries and provides a fluent, expressive syntax for working with the database. The Eloquent ORM maps the model's attributes to database columns, handles data retrieval and manipulation, and maintains the relationships between models.

// Overall, the User model serves as an intermediary between your application's code and the database. It provides a convenient and intuitive way to interact with user data and perform CRUD (Create, Read, Update, Delete) operations on user records.


///////////////////////////////////////////
// The User model in Laravel is tightly associated with the users table in the database. This association is established through the naming conventions and configuration settings in Laravel's Eloquent ORM.

// By default, Laravel assumes that the users table in the database corresponds to the User model. This is determined by the plural form of the model's class name (User) and the lowercase version of it being used as the table name (users). Laravel follows a convention-over-configuration approach, where it infers the table name based on the model's name unless explicitly specified.

// The User model is tightly linked to a row in the users table through various features and functionalities provided by Laravel's Eloquent ORM:

// Data Retrieval: When you query the User model using methods like find(), first(), or where(), Laravel generates SQL queries to fetch the corresponding data from the users table. The retrieved data is then mapped to the attributes of the User model instance.

// Data Modification: When you modify the attributes of a User model instance and call the save() method, Laravel translates this action into an SQL query that updates the corresponding row in the users table.

// Relationships: The User model can define relationships with other models, such as hasMany, hasOne, or belongsToMany. These relationships are defined based on foreign key constraints in the database and allow you to retrieve related data from other tables.

// Model Events: Laravel provides model events like creating, created, updating, updated, etc., which are triggered when certain actions are performed on the model. These events allow you to hook into the lifecycle of the model and perform additional tasks, such as updating related records or performing calculations.

// By adhering to Laravel's conventions and utilizing the features provided by Eloquent, the User model is tightly bound to the users table in the database. This tight coupling ensures that you can seamlessly interact with user data through the model and perform CRUD (Create, Read, Update, Delete) operations on the corresponding database table.







////////////////////////////////////////////////////////////////////////

// but in our code where are the markers that it is tight to our database which is mySQL?

// ChatGPT
// In the code you provided, the tight association between the User model and the MySQL database is implied through Laravel's Eloquent ORM. Here are a few markers that indicate the connection:

// Extending Authenticatable: The User model extends the Authenticatable class, which is provided by Laravel. This class includes the necessary functionality for user authentication, and it assumes that the user data is stored in a database. By extending this class, the User model inherits the authentication-related features and database connectivity.

// Configuration Settings: Laravel's configuration files, specifically config/database.php, define the default database connection and settings. By default, Laravel uses MySQL as the database system. You can modify the database connection settings in this file to match your MySQL configuration.

// Table Name Convention: The User model follows Laravel's naming convention, where the plural form of the model's class name (User) is used as the default table name (users). Laravel assumes that the users table in the database corresponds to the User model. However, you can explicitly specify a different table name if needed by setting the $table property in the User model.

// Eloquent Queries: Throughout your code, when interacting with the User model, you can use various Eloquent query methods like find(), where(), and save(). These methods generate SQL queries that operate on the users table, retrieving, updating, or inserting data as needed.

// Relationships: The User model defines a relationship with the Post model using the hasMany() method. This relationship indicates that a user can have multiple posts. Laravel establishes this relationship by assuming that the posts table has a foreign key column named user_id, which references the id column in the users table.

// While the code itself doesn't explicitly mention MySQL, Laravel's underlying database layer and conventions assume the use of a SQL database, and by default, it's MySQL. These markers and assumptions collectively indicate the tight association between the User model and the MySQL database.



/////////////////////
// The feedPosts() function is a method defined in a model, which establishes a relationship between the current model (let's say it's the User model) and another model (Post model) using a "has-many-through" relationship.

// Let's break down the code to understand it better:

// php
// Copy code
// public function feedPosts()
// {
//     return $this->hasManyThrough(Post::class, Follow::class, 'user_id', 'user_id', 'id', 'followeduser');
// }
// The function name feedPosts() is arbitrary and can be named differently based on the context and purpose of the relationship.

// return $this->hasManyThrough(...): This line indicates that the current model (in this case, the User model) has a "has-many-through" relationship with another model (the Post model).

// Post::class: This specifies the model class of the target model, which is the Post model in this case. It represents the "final" model you want to retrieve the data from.

// Follow::class: This specifies the intermediate model between the current model (User) and the target model (Post). In this case, it is the Follow model. The intermediate model is used to establish the relationship between the two models.

// 'user_id': This is the foreign key on the intermediate model (Follow) that represents the relationship with the current model (User). It indicates that the user_id column on the Follow model corresponds to the id column on the User model.

// 'user_id': This is the foreign key on the target model (Post) that represents the relationship with the intermediate model (Follow). It indicates that the user_id column on the Post model corresponds to the user_id column on the Follow model.

// 'id': This is the primary key column of the current model (User). It indicates that the primary key column on the User model is named id.

// 'followeduser': This is the foreign key column on the intermediate model (Follow) that corresponds to the primary key column of the target model (Post). It indicates that the followeduser column on the Follow model corresponds to the primary key column (id) on the Post model.

// In summary, the feedPosts() function defines a "has-many-through" relationship between the User model and the Post model, using the intermediate Follow model. It allows you to retrieve a collection of Post models that are associated with the current User model through the Follow model based on the defined foreign key relationships. This relationship can be used to fetch the posts from users that the current user follows, creating a "feed" of posts.