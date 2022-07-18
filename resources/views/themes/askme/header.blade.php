<!DOCTYPE html>
<!--[if IE 8 ]>
<html class="ie ie8" lang="en"> <![endif]-->
<!--[if IE 9 ]>
<html class="ie ie9" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html lang="en"> <!--<![endif]-->
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <title>Ask me – @yield('title')</title>
    <meta name="description" content="Ask me Responsive Questions and Answers Template">
    <meta name="author" content="2code.info">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="{{asset('/askme/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('/askme/css/skins/blue.css')}}">
    <link rel="stylesheet" href="{{asset('/askme/css/responsive.css')}}">
    <link rel="shortcut icon" href="{{asset('/askme/images/favicon.png')}}">
    @stack('css')

</head>
<body>

<div class="loader">
    <div class="loader_html"></div>
</div>

<div id="wrap" class="grid_1200">

    <div class="login-panel">
        <section class="container">
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
                                               name="login" value="{{ old('login') }}" required autocomplete="login"
                                               autofocus>
                                        <i class="icon-user"></i>
                                        @error('login')
                                        <span class="alert alert-danger" data-slideLoginPanel="1" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </p>
                                    <p class="login-password">
                                        <input type="password" type="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                               name="password"
                                               required autocomplete="current-password">
                                        <i class="icon-lock"></i>
                                        @error('password')
                                        <span class="alert alert-danger" data-slideLoginPanel="1" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </p>
                                </div>
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
                    <div class="page-content Register">
                        <h2>Register Now</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi adipiscing gravdio, sit amet
                            suscipit risus ultrices eu. Fusce viverra neque at purus laoreet consequa. Vivamus vulputate
                            posuere nisl quis consequat.</p>
                        <a class="button color small signup">Create an account</a>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="panel-pop" id="signup">
        <h2>Register Now<i class="icon-remove"></i></h2>
        <div class="form-style form-style-3">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-inputs clearfix">
                    <p>
                        <label class="required">{{ __('Login') }}<span>*</span></label>
                        <input type="text" class="form-control @error('login', 'register') is-invalid @enderror"
                               name="login" value="{{ old('login') }}" required autocomplete="login" autofocus>
                        @error('login', 'register')
                        <span class="alert alert-danger" data-showRegisterForm="1" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </p>
                    <p>
                        <label class="required">{{ __('Email Address') }}<span>*</span></label>
                        <input type="email" class="form-control @error('email', 'register') is-invalid @enderror"
                               name="email" value="{{ old('email') }}" required autocomplete="email">
                        @error('email', 'register')
                        <span class="alert alert-danger" data-showRegisterForm="1" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </p>
                    <p>
                        <label class="required">{{ __('Password') }}<span>*</span></label>
                        <input type="password" class="form-control @error('password', 'register') is-invalid @enderror"
                               name="password" required autocomplete="new-password">
                        @error('password', 'register')
                        <span class="alert alert-danger" data-showRegisterForm="1" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </p>
                    <p>
                        <label class="required">{{ __('Confirm Password') }}<span>*</span></label>
                        <input type="password" class="form-control" name="password_confirmation" required
                               autocomplete="new-password">
                    </p>
                </div>
                <p class="form-submit">
                    <input type="submit" value="{{ __('Register') }}" class="button color small submit">
                </p>
            </form>
        </div>
    </div>

    <div class="panel-pop" id="lost-password">
        <h2>Lost Password<i class="icon-remove"></i></h2>
        <div class="form-style form-style-3">
            <p>Lost your password? Please enter your username and email address. You will receive a link to create a new
                password via email.</p>
            <form>
                <div class="form-inputs clearfix">
                    <p>
                        <label class="required">Username<span>*</span></label>
                        <input type="text">
                    </p>
                    <p>
                        <label class="required">E-Mail<span>*</span></label>
                        <input type="email">
                    </p>
                </div>
                <p class="form-submit">
                    <input type="submit" value="Reset" class="button color small submit">
                </p>
            </form>
            <div class="clearfix"></div>
        </div>
    </div>

    <div id="header-top">
        <section class="container clearfix">
            <nav class="header-top-nav">
                <ul>
                    <li>
                        @auth
                            <a href="{{ route('logout') }}" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                                <i class="icon-remove"></i>{{ __('Logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        @else
                            <a href="login.html" id="login-panel"><i class="icon-user"></i>Login Area</a>
                        @endauth
                    </li>
                </ul>
            </nav>
            <div class="header-search">
                <form action="{{route('search')}}">
                    <input name="query" type="text" value="Search here ..." onfocus="if(this.value=='Search here ...')this.value='';"
                           onblur="if(this.value=='')this.value='Search here ...';" autocomplete="off">
                    <button type="submit" class="search-submit"></button>
                </form>
            </div>
        </section>
    </div>
    <header id="header">
        <section class="container clearfix">
            <div class="logo"><a href="{{route('home')}}"><img alt="" src="{{asset('/askme/images/logo.png')}}"></a>
            </div>
            <nav class="navigation">
                <ul>
                    <li class="current_page_item"><a href="{{route('home')}}">Home</a></li>
                    <li class="ask_question"><a href="{{route('questions.create')}}">Ask Question</a></li>
                    @auth
                        <li><a href="{{route('users.profile.show', ['user' => auth()->user()])}}">User</a></li>
                    @endauth
                </ul>
            </nav>
        </section>
    </header>