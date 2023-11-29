<?php

namespace App\Http\Controllers;

use App\Jobs\SendNewPostEmail;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PostController extends Controller
{
    public function search($term)
    {
        $posts = Post::search($term)->get();
        $posts->load('user:id,username,avatar');
        return $posts;
    }

    public function actuallyUpdate(Post $post, Request $request)
    {
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);

        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);

        // return $incomingFields;
        $post->update($incomingFields);

        return back()->with('success', 'Post successfully updated');
    }

    public function showEditForm(Post $post)
    {
        return view('edit-post', ['post' => $post]);
    }


    public function delete(Post $post)
    {
        // if (auth()->user()->cannot('delete', $post)) {
        //     return 'You cannot do that';
        // }

        $post->delete();
        return redirect('/profile/' . auth()->user()->username)->with('success', 'Post successfully deleted.');
    }

    public function deleteApi(Post $post)
    {
        $post->delete();
        return 'true';
    }

    public function viewSinglePost(Post $post)
    {
        // if ($post->user_id === auth()->user()->id) {
        //     return 'you are author';
        // }
        // return 'You are NOT author';
        // return $post->user;
        // return $post;
        $ourHTML = Str::markdown($post->body);
        $post['body'] = $ourHTML;
        return view('single-post', ['post' => $post]);
    }

    public function storeNewPost(Request $request)
    {
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);

        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id();

        $newPost =  Post::create($incomingFields);

        dispatch(new SendNewPostEmail(['sendTo' => auth()->user()->email, 'name' => auth()->user()->username, 'title' => $newPost->title]));

        return redirect("/post/{$newPost->id}")->with('success', 'New Post Successfully created');
    }

    public function storeNewPostApi(Request $request)
    {
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);

        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id();

        $newPost = Post::create($incomingFields);

        dispatch(new SendNewPostEmail(['sendTo' => auth()->user()->email, 'name' => auth()->user()->username, 'title' => $newPost->title]));

        return $newPost->id;
    }


    public function showCreateForm()
    {
        return view('create-post');
    }
}

///When Laravel retrieves the corresponding Post model based on the route parameter, it means that Laravel fetches the specific row of data from the posts table in your database that matches the given route parameter. Laravel uses the Post model to represent this row of data. 
// The Post model extends the Model class provided by Laravel's Eloquent ORM (Object-Relational Mapping). This inheritance allows the Post model to inherit various methods and functionalities provided by the Model class, such as querying, retrieving, and manipulating data from the corresponding database table (posts).

//php artisan make:controller PostController

//The PostController is a class responsible for handling various HTTP requests related to posts in your application.

// A Post model instance refers to an object of the Post model class that represents a single post in your application. In the given context, when a user visits a URL that matches the route associated with the viewSinglePost method, which is defined as /posts/{post}, Laravel automatically fetches the corresponding Post model instance based on the value of the {post} parameter.

// For example, if the URL is /posts/1, the {post} parameter will have a value of 1. Laravel will then look for a Post model with an ID of 1 in the database and fetch that specific post. This fetched Post model instance is then passed as a parameter to the viewSinglePost method.

// By type-hinting the parameter with Post $post, Laravel knows to fetch the Post model instance automatically based on the value of the {post} parameter from the database. This is known as route model binding. So within the viewSinglePost method, you can directly access the properties and methods of the fetched Post model instance, allowing you to work with the specific post data and perform further operations if needed.

////
// Yes, when we refer to a "Post model instance," it means an instance of the Post model class that represents a specific record or entry in the corresponding database table. In Laravel, the Eloquent ORM (Object-Relational Mapping) allows you to interact with your database using models.

// When you fetch a Post model instance, it represents a specific row or record in the "posts" table of your database. Each instance of the Post model corresponds to a specific post entry in the table, containing attributes (such as title, body, user_id, etc.) that hold the data for that particular post.

// So, when you use route model binding and type-hint the parameter as Post $post in the viewSinglePost method, Laravel will automatically retrieve the corresponding Post model instance from the database based on the value provided in the URL. This retrieved instance represents a specific post in the database, allowing you to access and work with its data within the method.

////////////////////////////
// In Laravel, when defining routes, you can specify route parameters using curly braces {}. For example, in the route /posts/{post}, the {post} is a route parameter that acts as a placeholder for the actual value.

// When a request is made to a route with a route parameter, Laravel will automatically extract the value of that parameter from the URL. For example, if the URL is /posts/1, Laravel will extract 1 as the value of the post parameter.

// To determine which post to retrieve, Laravel uses the route parameter name (post in this case) and the value extracted from the URL (1 in this case). Laravel then performs a database query based on this information to fetch the corresponding post.

// Under the hood, Laravel uses route model binding to handle this process automatically. It maps the route parameter to the corresponding model by using the model's type hinting in the controller method. In the case of viewSinglePost() method, the parameter $post is type-hinted with the Post model, indicating that Laravel should retrieve a Post model instance based on the value of the post route parameter.

// Laravel uses the value of the route parameter to perform a database query, typically looking for a record with a matching identifier (usually the id column) in the posts table. Once the model instance is retrieved, it is passed as an argument to the controller method ($post in this case), allowing you to work with the specific post within that method.


///Route::get('/posts/{post}', [PostController::class, 'viewSinglePost']);

// Yes, you're correct. In the function viewSinglePost(Post $post), the parameter $post is of type Post. Laravel's route model binding feature automatically resolves this parameter by retrieving the corresponding Post model based on the route parameter.

// Let's say you have a route definition like this:

// php
// Copy code
// Route::get('/posts/{post}', [PostController::class, 'viewSinglePost']);
// In this route definition, {post} is a route parameter that represents the identifier of a specific post. When a request is made to this route with a valid post identifier, Laravel will automatically retrieve the corresponding Post model from the database and inject it as the $post parameter in the viewSinglePost() method of the PostController.

// This means that you don't need to manually fetch the Post model from the database or handle the route parameter retrieval yourself. Laravel takes care of this process for you through route model binding, making it convenient to work with specific models directly in your controller methods.

// By type-hinting the parameter as Post, you're indicating to Laravel that you expect an instance of the Post model to be injected. Laravel will handle the retrieval and injection process behind the scenes, allowing you to work with the retrieved Post model within your controller method.

// This feature helps simplify your controller code and allows you to focus on handling the retrieved model without worrying about the retrieval logic.