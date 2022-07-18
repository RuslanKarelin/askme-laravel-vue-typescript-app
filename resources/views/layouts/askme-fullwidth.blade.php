@include('themes.askme.header')
    @yield('after-header-block')
    <section class="container @yield('container-class', 'main-content')">
        @yield('content')
    </section>
@include('themes.askme.footer')