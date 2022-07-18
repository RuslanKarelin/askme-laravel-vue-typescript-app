<div class="page-content">
    <p><a href="{{route('users.profile.show', ['user' => $user])}}" class="button color small">Show Profile</a></p>
    <div class="boxedtitle page-title"><h2>Edit Profile</h2></div>
    <div class="form-style form-style-4">
        <form method="POST" action="{{route('users.profile.update', ['user' => $user])}}"  enctype="multipart/form-data">
            @csrf()
            @method('PATCH')
            <div class="form-inputs clearfix">
                <p>
                    <label>First Name</label>
                    <input type="text" name="profile[first_name]" value="{{old('profile[first_name]', $user->profile->first_name)}}">
                </p>
                <p>
                    <label>Last Name</label>
                    <input type="text" name="profile[last_name]" value="{{old('profile[last_name]', $user->profile->last_name)}}">
                </p>
                <p>
                    <label>Password</label>
                    <input type="password" value="" name="password">
                    @error('password')
                    <span class="alert alert-danger">{{ $message }}</span>
                    @enderror
                </p>
                <p>
                    <label>Confirm Password</label>
                    <input type="password" value="" name="password_confirmation">
                </p>
                <div class="clearfix"></div>
                <p>
                    <label class="required">E-Mail<span>*</span></label>
                    <input required type="email" name="email" value="{{old('email', $user->email)}}">
                    @error('email')
                    <span class="alert alert-danger">{{ $message }}</span>
                    @enderror
                </p>
            </div>
            <div class="form-style form-style-2">
                <div class="user-profile-img">
                    @if (file_exists(storage_path('app/public/'.config('storage.avatars').$user->id.'/'.$user->profile->avatar)))
                    <img src="{{asset('storage/'.config('storage.avatars').$user->id.'/'.$user->profile->avatar)}}" alt="admin">
                    @endif
                </div>
                <p class="user-profile-p">
                    <label>Profile Picture</label>
                <div class="fileinputs">
                    <input type="file" class="file" name="profile[avatar]">
                    <div class="fakefile">
                        <button type="button" class="button small margin_0">Select file</button>
                        <span><i class="icon-arrow-up"></i>Browse</span>
                    </div>
                </div>
                @error('profile.avatar')
                <div class="clearfix"></div>
                <span class="alert alert-danger">{{ $message }}</span>
                @enderror
                <p></p>
                <div class="clearfix"></div>
                <p>
                    <label>About Yourself</label>
                    <textarea cols="58" rows="8" name="profile[about]">{{old('profile[about]', $user->profile->about)}}</textarea>
                </p>
            </div>
            <p class="form-submit">
                <input type="submit" value="Update" class="button color small login-submit submit">
            </p>
        </form>
    </div>
</div>