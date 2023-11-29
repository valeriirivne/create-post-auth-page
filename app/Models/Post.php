<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{

    use Searchable;
    use HasFactory;

    protected $fillable = ['title', 'body', 'user_id'];

    public function toSearchableArray()
    {
        return [
            'title' => $this->title,
            'body' => $this->body
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

//php artisan make:model Post

// In Laravel we don't write mySql code ourselves, we use something called Model, which make it really to perform database actions for us
// Model is a class that interacts with the database.

// In Laravel, the naming convention for database tables associated with models is based on the plural form of the model's class name. In the case of the User model, Laravel assumes that the corresponding database table is named users. This naming convention follows the principle of "convention over configuration" that Laravel promotes.