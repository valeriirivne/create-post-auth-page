<?php

use App\Events\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FollowController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/admins-only', function () {
    if (Gate::allows('visitAdminPages')) {
        return 'Only admins should be able to see this page.';
    }
    return 'You can not view this page';
});


//User related routes
Route::get('/', [UserController::class, 'showCorrectHomePage'])->name('login');
Route::post('/register', [UserController::class, 'register'])->middleware('guest');
Route::post('/login', [UserController::class, 'login'])->middleware('guest');
Route::post('/logout', [UserController::class, 'logout'])->middleware('mustBeLoggedIn');
Route::get('/manage-avatar', [UserController::class, 'showAvatarForm'])->middleware('mustBeLoggedIn');
Route::post('/manage-avatar', [UserController::class, 'storeAvatar']);


//Follow related routes
Route::post('/create-follow/{user:username}', [FollowController::class, 'createFollow'])->middleware('mustBeLoggedIn');
Route::post('/remove-follow/{user:username}', [FollowController::class, 'removeFollow'])->middleware('mustBeLoggedIn');


//blog post related routes
Route::get('/create-post', [PostController::class, 'showCreateForm'])->middleware('mustBeLoggedIn');
Route::post('/create-post', [PostController::class, 'storeNewPost'])->middleware('mustBeLoggedIn');
Route::get('/post/{post}', [PostController::class, 'viewSinglePost']);
Route::delete('/post/{post}', [PostController::class, 'delete'])->middleware('can:delete,post');
Route::get('/post/{post}/edit', [PostController::class, 'showEditForm'])->middleware('can:update,post');
Route::put('/post/{post}', [PostController::class, 'actuallyUpdate'])->middleware('can:update,post');
Route::get('/search/{term}', [PostController::class, 'search']);


//Profile related routes
Route::get('/profile/{user:username}', [UserController::class, 'profile']);
Route::get('/profile/{user:username}/followers', [UserController::class, 'profileFollowers']);
Route::get('/profile/{user:username}/following', [UserController::class, 'profileFollowing']);

Route::middleware('cache.headers:public;max_age=20;etag')->group(function () {
    Route::get('/profile/{user:username}/raw', [UserController::class, 'profileRaw']);
    Route::get('/profile/{user:username}/followers/raw', [UserController::class, 'profileFollowersRaw']);
    Route::get('/profile/{user:username}/following/raw', [UserController::class, 'profileFollowingRaw']);
});

//Chat Routes
Route::post('/send-chat-message', function (Request $request) {
    $formFields = $request->validate([
        'textvalue' => 'required'
    ]);

    if (!trim(strip_tags($formFields['textvalue']))) {
        return response()->noContent();
    }

    broadcast(new ChatMessage(['username' => auth()->user()->username, 'textvalue' => strip_tags($request->textvalue), 'avatar' => auth()->user()->avatar]))->toOthers();
    return response()->noContent();
})->middleware('mustBeLoggedIn');


// In the code snippet you provided, ExampleController is a controller class used to handle specific routes in your application. Let's break down the code and explain each section:

//     use App\Http\Controllers\ExampleController;: This line imports the ExampleController class so that it can be used in the code.
    
//     Route definitions: The next section defines the routes for your application using the Route:: syntax provided by Laravel.
    
//     Route::get('/', [ExampleController::class, 'homepage']);: This line defines a GET route for the root URL ("/"). When a user visits the root URL, the homepage method of the ExampleController class will be executed to handle the request.
    
//     Route::get('/about', [ExampleController::class, 'aboutPage']);: This line defines a GET route for the "/about" URL. When a user visits the "/about" page, the aboutPage method of the ExampleController class will be executed to handle the request.
    
//     Route::post('/register', [UserController::class, 'register']);: This line defines a POST route for the "/register" URL. When a user submits a form to register, the register method of the UserController class will be executed to handle the registration process.
    
//     Route::post('/login', [UserController::class, 'login']);: This line defines a POST route for the "/login" URL. When a user submits a login form, the login method of the UserController class will be executed to handle the login process.
    
//     The ExampleController and UserController classes are responsible for handling the logic and actions associated with these routes. The methods (homepage, aboutPage, register, login) within these controller classes will contain the specific code to process the requests and return the appropriate responses.
    
//     To summarize, the ExampleController class is used to define the behavior and actions for the routes related to the homepage and about page. The UserController class is responsible for handling the registration and login routes. Each method within these controllers contains the logic to process the corresponding requests and provide the necessary response.