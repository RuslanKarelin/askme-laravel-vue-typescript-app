@include('themes.askme.header')
    @yield('after-header-block')
    <section class="container @yield('container-class', 'main-content')">
        <div class="row">
            <div class="col-md-9">
                @yield('content')
            </div>
            <aside class="col-md-3 sidebar">
                @yield('sidebar')
            </aside>
        </div>
    </section>
@include('themes.askme.footer')