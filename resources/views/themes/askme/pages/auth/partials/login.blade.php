<div class="login">
    <div class="row">
        <div class="col-md-6">
            <div class="page-content">
                <h2>Login</h2>
                <div class="form-style form-style-3">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-inputs clearfix">
                            <p class="login-text">
                                <input type="text" class="form-control @error('login') is-invalid @enderror"
                                       name="login" value="{{ old('login') }}" required autocomplete="login" autofocus>
                                <i class="icon-user"></i>
                                @error('login')
                                <span class="alert alert-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </p>
                            <p class="login-password">
                                <input type="password" type="password"
                                       class="form-control @error('password') is-invalid @enderror" name="password"
                                       required autocomplete="current-password">
                                <i class="icon-lock"></i>

                                @error('password')
                                <span class="alert alert-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </p>
                        </div>
                        @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                        <p class="form-submit login-submit">
                            <input type="submit" value="Log in" class="button color small login-submit submit">
                        </p>
                        <div class="rememberme">
                            <label><input type="checkbox" checked="checked"> Remember Me</label>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="page-content">
                <h2>Register Now</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi adipiscing gravdio, sit amet suscipit
                    risus ultrices eu. Fusce viverra neque at purus laoreet consequa. Vivamus vulputate posuere nisl
                    quis consequat.</p>
                <a class="button small color signup">Create an account</a>
            </div>
        </div>
    </div>
</div>