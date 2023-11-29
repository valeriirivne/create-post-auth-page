<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use App\Events\OurExampleEvent;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    public function storeAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|max:3000'
        ]);

        $user = auth()->user();

        $filename = $user->id . '-' . uniqid() . '.jpg';

        $imgData = Image::make($request->file('avatar'))->fit(120)->encode('jpg');
        Storage::put('public/avatars/' . $filename, $imgData);

        $oldAvatar = $user->avatar;

        $user->avatar = $filename;
        $user->save();

        if ($oldAvatar != '/fallback-avatar.jpg') {
            Storage::delete(str_replace('/storage/', 'public/', $oldAvatar));
        }

        return back()->with('success', 'Congrats on the new avatar.');
    }

    public function showAvatarForm()
    {
        return view('avatar-form');
    }

    private function getSharedData($user)
    {
        $currentlyFollowing = 0;

        if (auth()->check()) {
            $currentlyFollowing = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->count();
        }

        View::share('sharedData', ['currentlyFollowing' => $currentlyFollowing, 'avatar' => $user->avatar, 'username' => $user->username, 'postCount' => $user->posts()->count(), 'followerCount' => $user->followers()->count(), 'followingCount' => $user->followingTheseUsers()->count()]);
    }

    public function profile(User $user)
    {
        $this->getSharedData($user);
        // $thePosts = $user->posts()->get();
        // return $thePosts;
        // return $user;
        return view('profile-posts', ['posts' => $user->posts()->latest()->get()]);
    }

    public function profileRaw(User $user)
    {

        // $thePosts = $user->posts()->get();
        // return $thePosts;
        // return $user;
        // return view('profile-post', ['posts' => $user->posts()->latest()->get()]);
        return response()->json(['theHTML' => view('profile-posts-only', ['posts' => $user->posts()->latest()->get()])->render(), 'docTitle' => $user->username . "'s Profile"]);
    }

    public function profileFollowers(User $user)
    {
        $this->getSharedData($user);

        // return $user->followers()->latest()->get();
        return view('profile-followers', ['followers' => $user->followers()->latest()->get()]);
    }

    public function profileFollowersRaw(User $user)
    {
        return response()->json(['theHTML' => view('profile-followers-only', ['followers' => $user->followers()->latest()->get()])->render(), 'docTitle' => $user->username . "'s Followers"]);
    }

    public function profileFollowing(User $user)
    {
        $this->getSharedData($user);
        // return $user->followingTheseUsers()->latest()->get();
        return view('profile-following', ['following' => $user->followingTheseUsers()->latest()->get()]);
    }

    public function profileFollowingRaw(User $user)
    {
        return response()->json(['theHTML' => view('profile-following-only', ['following' => $user->followingTheseUsers()->latest()->get()])->render(), 'docTitle' => 'Who ' . $user->username . " Follows"]);
    }


    public function logout()
    {
        event(new OurExampleEvent(['username' => auth()->user()->username, 'action' => 'logout']));
        auth()->logout();

        return redirect('/')->with('success', 'You are now logged out');
    }

    public function showCorrectHomepage()
    {
        if (auth()->check()) {
            return  view('homepage-feed', ['posts' => auth()->user()->feedPosts()->latest()->paginate(2)]);
        } else {
            // $postCount = Cache::has()
            // if (Cache::has('postCount')) {
            //     $postCount = Cache::get('postCount');
            // } else { 
            //     sleep(5);
            //     $postCount = Post::count();
            //     Cache::put('postCount', $postCount, 20);
            $postCount = Cache::remember('postCount', 20, function () {
                // sleep(5);
                return Post::count();
            });
            return view('homepage', ['postCount' => $postCount]);
        }
    }

    public function loginApi(Request $request)
    {
        $incomingFields = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (auth()->attempt($incomingFields)) {
            $user = User::where('username', $incomingFields['username'])->first();
            // return $user;
            $token = $user->createToken('ourapptoken')->plainTextToken;
            return $token;
        }
        return 'sorry';
    }



    public function login(Request $request)
    {
        $incomingFields = $request->validate([
            'loginusername' => 'required',
            'loginpassword' => 'required'
        ]);
        if (auth()->attempt([
            'username' => $incomingFields['loginusername'],
            'password' => $incomingFields['loginpassword']
        ])) {
            $request->session()->regenerate();
            //in this exact moment we are saying that event we created is happening
            event(new OurExampleEvent(['username' => auth()->user()->username, 'action' => 'login']));
            return redirect('/')->with('success', 'You have successfully login');
        } else {
            return redirect('/')->with('failure', 'Invalid login.');
        }
    }

    public function register(Request $request)
    {

        $incomingFields = $request->validate([
            'username' => ['required', 'min:3', 'max:30', Rule::unique('users', 'username')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8', 'confirmed'],

        ]);

        $incomingFields['password'] = bcrypt($incomingFields['password']);

        $user = User::create($incomingFields);
        auth()->login($user);

        return redirect('/')->with('success', 'Thank you for creating an account');
    }
}


// The profile function in the UserController is responsible for displaying the profile page of a user. Let's break down the function for a beginner:

//     php
//     Copy code
//     public function profile(User $username)
//     {
//         $thePosts = $username->posts()->get();
//         return $thePosts;
//         return view('profile-post', ['username' => $username->username]);
//     }
//     The profile function accepts a parameter $username of type User. This parameter is automatically resolved by Laravel's route model binding. It means that when this function is called, Laravel will automatically retrieve the corresponding User model based on the route parameter.
    
//     Inside the function, $username->posts() is used to retrieve all the posts associated with the user. The posts() method is a relationship defined in the User model using the hasMany relationship type. It indicates that a user can have multiple posts.
    
//     The get() method is then called on the posts() relationship to retrieve the actual post records from the database. The retrieved posts are assigned to the variable $thePosts.
    
//     Finally, the function returns the $thePosts variable, which contains the collection of posts. Additionally, it also returns a view called 'profile-post', passing the $username->username value to the view. This view is responsible for rendering the profile page and displaying the user's posts.
    
//     In summary, the profile function retrieves the user's posts from the database and returns them along with the user's username to a view, which is then responsible for displaying the profile page.

//////////////////////////////////////////


// In the User model, the posts function defines a relationship between the User model and the Post model. Specifically, it establishes a "one-to-many" relationship, indicating that a user can have multiple posts.

// Here's what each part of the function does:

// return $this: The $this keyword refers to the current instance of the User model. By returning $this, we are returning the current user object.

// hasMany(Post::class, 'user_id'): This method call specifies the type of relationship between the User and Post models. The hasMany method is used to define a "one-to-many" relationship, indicating that a user can have multiple posts. It takes two parameters:

// Post::class: This specifies the related model class, which is Post in this case. It allows Laravel to understand that the relationship is with the Post model.
// 'user_id': This indicates the foreign key column in the posts table that references the users table. In this case, it assumes that the posts table has a column named user_id that references the id column in the users table.
// By defining the posts relationship in the User model, Laravel now knows that we can retrieve all the posts associated with a user using the posts method on a User instance.

// For example, if you have a $user object representing a user, you can retrieve all their posts by calling $user->posts. This will return a collection of Post objects associated with that user.

// Overall, the posts function in the User model helps establish and define the relationship between a user and their posts, allowing us to easily retrieve posts associated with a user.

/////////////////////////////////////////////////////////////////////////

// // The User::create($incomingFields) line creates a new User record in the database. The create method is a convenient way to create a new record and save it to the database.

// ///////////////////////////////////////////

// // The login function is responsible for handling the login process when a user submits the login form.

// // $request is an instance of the Request class, which contains the information and data of the incoming HTTP request.

// // $incomingFields is an array that stores the validated form input fields for the login form. The $request->validate() method is used to validate the input fields based on the provided validation rules.

// // In this case, the validation rules state that both the "loginusername" and "loginpassword" fields are required. If any of these fields are missing, the validation will fail, and Laravel will automatically return an appropriate error response.

// // The auth()->attempt() method is used to attempt to authenticate the user. It checks if the provided username and password match any user record in the database.

// // Inside the auth()->attempt() method, an associative array is passed with the username and password values obtained from the validated form input fields.

// // If the authentication attempt is successful, meaning the username and password match a user record in the database, the code block inside the if statement will be executed, and the string "Congrats" will be returned.

// // If the authentication attempt fails, the code block inside the else statement will be executed, and the string "Sorry" will be returned.

// // In summary, the login function validates the username and password fields from the login form, attempts to authenticate the user using the provided credentials, and returns a response based on the authentication result.




// ///////////////////////////////////

// // The session identifier itself is not stored directly in the laravel_session cookie. Instead, the cookie contains a unique encrypted value that represents the session identifier. Let me clarify the process for you:

// //     When a user visits your Laravel application, Laravel generates a unique session identifier for that user's session on the server-side.
    
//     The session identifier is stored on the server-side, typically in a session storage mechanism like files, database, or cache.
    
//     Laravel encrypts the session identifier and stores the encrypted value in the laravel_session cookie.
    
//     The laravel_session cookie is then sent back and forth between the user's browser and the server with each subsequent request.
    
//     On each request, Laravel reads the laravel_session cookie and decrypts the encrypted value to obtain the session identifier.
    
//     Using the decrypted session identifier, Laravel retrieves the corresponding session data from the server-side storage.
    
//     The retrieved session data is then made available to your Laravel application for use during that specific request.
    
//     On subsequent requests, the process is repeated. Laravel reads the laravel_session cookie, decrypts the encrypted value to obtain the session identifier, and retrieves the associated session data.
    
//     In summary, the session identifier itself is not stored in the laravel_session cookie directly. Instead, the cookie contains an encrypted value that represents the session identifier. Laravel uses this encrypted value to retrieve the session data associated with the user's session on subsequent requests. The encryption ensures the security and integrity of the session identifier and prevents tampering by external parties.

/////

// When you are running your Laravel application in development mode on your computer using localhost:8000, the server being used is the built-in PHP development server provided by Laravel. It is a lightweight web server that is included with Laravel for development purposes.

// The PHP development server is not meant for production use but is convenient for local development and testing. It allows you to run your Laravel application without the need for a separate web server like Apache or Nginx.

// When you run the php artisan serve command, Laravel starts the PHP development server, and it listens on localhost:8000 by default. This server handles incoming requests to your application, processes them, and sends back the responses.

// In the context of the session storage, when running in development mode, the default session driver ('file') stores the session data as files in the storage/framework/sessions directory of your Laravel application on your local computer. This allows the session data to persist across multiple requests while you are testing and developing your application locally.

// So, during development, the server being used is the built-in PHP development server provided by Laravel, and the session data is stored on your local computer's disk in the specified directory.

/////////
// In Laravel, the return redirect('/')->with('success', 'You have successfully login') method is used to redirect the user to a specific URL and also flash a success message to the session. Let's break down the code and explain each part:




//     so we retrieve the followers based on the id of the user who are logged in?
// ChatGPT
// No, the followers are retrieved based on the user whose profile is being viewed, not the user who is currently logged in.

// In the provided code, the profileFollowers() method in the UserController retrieves the followers for a specific user:

// php
// Copy code
// public function profileFollowers(User $user)
// {
//     $this->getSharedData($user);

//     return $user->followers()->latest()->get();
//     // ...
// }
// The $user parameter is an instance of the User model, representing the user whose profile is being viewed. The followers() method defined in the User model establishes the relationship between the User model and the Follow model. It uses the hasMany() method to define a one-to-many relationship, where a user can have multiple followers.

// By calling $user->followers(), you retrieve the followers associated with the $user instance. The latest()->get() method is then used to retrieve the followers, with the latest() method ordering the results by the most recent follower and get() retrieving the results as a collection.

// So, when you access the /profile/{user:username}/followers route, it will display the followers of the user whose username is provided in the URL. The followers are retrieved based on the ID of the user specified in the route, not the ID of the currently logged-in user.
////////////////////////////////////////
// so $user here is indicating on User.php model : return view('profile-followers', ['followers' => $user->followers()->latest()->get()]); Am I right?

// Yes, that's correct. In the code snippet return view('profile-followers', ['followers' => $user->followers()->latest()->get()]);, $user refers to an instance of the User model. The followers() method is called on that instance to retrieve the followers of the user.

// By using $user->followers(), you are accessing the followers() relationship function defined in the User model. This allows you to retrieve all the followers of the user. The latest() method is then chained to the relationship query to retrieve the followers in the latest order. Finally, the get() method is called to execute the query and retrieve the followers as a collection.

// The retrieved followers are then passed to the profile-followers view as the 'followers' variable, which can be accessed within the view to display the follower information.