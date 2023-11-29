<x-layout :doctitle="$post->title">
    
  <div class="container py-md-5 container--narrow">
    <div class="d-flex justify-content-between">
      <h2>{{$post->title}}</h2>
      @can('update', $post)
      <span class="pt-2">
        <a href="/post/{{$post->id}}/edit" class="text-primary mr-2" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-edit"></i></a>
        <form class="delete-post-form d-inline" action="/post/{{$post->id}}" method="POST">
          @csrf
          @method('DELETE')
          <button class="delete-post-button text-danger" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash"></i></button>
        </form>
      </span>
      @endcan
    </div>

    <p class="text-muted small mb-4">
      <a href="/profile/{{$post->user->username}}"><img class="avatar-tiny" src="{{$post->user->avatar}}" /></a>
      Posted by <a href="/profile/{{$post->user->username}}">{{$post->user->username}}</a> on {{$post->created_at->format('n/j/Y')}}
    </p>

    <div class="body-content">
   {!!$post->body!!}
    </div>
  </div>

</x-layout>

{{-- 
The server uses the specified URL to determine which route in the web.php file should handle the request. In Laravel, the web.php file defines the routes for your application.

In the given example, the form action is set to "/post/{{$post->id}}", which corresponds to the URL pattern defined in the web.php file as Route::delete('/post/{post}', [[PostController::class, 'delete']]);.

When the form is submitted with the DELETE method, the server receives the request with the specified URL, such as "/post/123" (assuming the post ID is 123). Laravel's routing system matches this URL pattern to the defined route in the web.php file.

The specified route tells Laravel that when a DELETE request is made to the "/post/{post}" URL, it should call the 'delete' method of the PostController class. The {post} parameter in the URL is automatically bound to the corresponding Post model based on the post ID provided in the URL. --}}

{{-- So, the server uses the specified URL to determine the appropriate route defined in the web.php file, and then calls the corresponding method in the specified controller to handle the request. --}}