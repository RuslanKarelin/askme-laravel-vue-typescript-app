<div class="user-profile">
    <div class="col-md-12">
        <div class="page-content">
            @can('edit', $user)
            <p class="edit-profile-btn"><a href="{{route('users.profile.edit', ['user' => $user])}}" class="button color small">Edit Profile</a></p>
            @endcan
            <h2>About {{$user->profile->fullName()}}</h2>
            <div class="user-profile-img">
                @if (file_exists(storage_path('app/public/'.config('storage.avatars').$user->id.'/'.$user->profile->avatar)))
                    <img width="60" height="60" src="{{asset('storage/'.config('storage.avatars').$user->id.'/'.$user->profile->avatar)}}" alt="admin">
                @endif
            </div>
            <div class="ul_list ul_list-icon-ok about-user">
                <ul>
                    <li><i class="icon-plus"></i>Registerd : <span>{{$user->created_at->format('M ,d, Y')}}</span></li>
                </ul>
            </div>
            <p>{{$user->profile->about}}</p>
            <div class="clearfix"></div>

        </div>
    </div>
    <div class="col-md-12">
        <div class="page-content page-content-user-profile">
            <div class="user-profile-widget">
                <h2>User Stats</h2>
                <div class="ul_list ul_list-icon-ok">
                    <ul>
                        <li><i class="icon-question-sign"></i><a href="user_questions.html">Questions<span> ( <span>{{$user->questions_count}}</span> ) </span></a></li>
                        <li><i class="icon-comment"></i><a href="user_answers.html">Answers<span> ( <span>{{$user->answers_count}}</span> ) </span></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>