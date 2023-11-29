<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;

    public function userDoingTheFollowing()
    {

        return $this->belongsTo(User::class, 'user_id');
    }

    public function userBeingFollowed()
    {
        return $this->belongsTo(User::class, 'followeduser');
    }
}


// Here's what each part of the code does:

// public function userDoingTheFollowing() declares a method named userDoingTheFollowing.
// return $this->belongsTo(User::class, 'user_id') defines the relationship with the User model using the belongsTo method.
// User::class is the class reference to the User model. It indicates that the relationship is with the User model.
// 'user_id' specifies the foreign key column in the Follow model's table that relates to the primary key of the User model's table.
// In simpler terms, this method establishes a relationship between a Follow model and a User model, where a Follow model "belongs to" a specific user (represented by the user_id foreign key). This allows you to retrieve the user who is performing the following action associated with a specific Follow model instance.

// You can access the user who is doing the following by calling the userDoingTheFollowing method on a Follow model instance, like $follow->userDoingTheFollowing. This will retrieve the associated User model instance.