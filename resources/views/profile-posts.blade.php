<x-profile :sharedData="$sharedData" doctitle="{{$sharedData['username']}}'s Profile">
  {{-- <x-profile  :avatar="$avatar" :username="$username" :currentlyFollowing="$currentlyFollowing" :postCount="$postCount"> --}}
@include('profile-posts-only')
</x-profile>  