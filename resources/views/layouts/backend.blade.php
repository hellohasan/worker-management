<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <title>{{ isset($page_title) ? $page_title . ' - ' : '' }} {{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('img/favicon.png') }}" type="image/gif" sizes="16x16">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link href="{{ mix('css/backend.css') }}" rel="stylesheet">
    @stack('styles')
</head>

<body class="hold-transition sidebar-mini layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Main Header -->
        @include('layouts.partials.navbar')

        <!-- Left side column. contains the logo and sidebar -->
        @include('layouts.partials.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @include('backend.partials.__breadcrumb', ['page_title' => $page_title])
            @include('backend.partials.__validation_error')
            @yield('content')
        </div>

        <!-- Main Footer -->
        <footer class="main-footer">
            <div class="float-right d-none d-sm-block"><b>Version</b> 3.0.5</div>
            <strong>Copyright &copy; 2017-{{ date('Y') }} <a href="#">QUICKTEAM</a>.</strong> All rights reserved.
        </footer>
    </div>

    @include('sweetalert::alert')
    <script src="{{ mix('js/backend.js') }}"></script>

    @stack('scripts')

    <script>
        $(document).ready(function() {
            var url = window.location;
            $('ul.nav-sidebar a').filter(function() {
                if (this.href)
                    return this.href == url;
            }).addClass('active');
            $('ul.nav-treeview a').filter(function() {
                if (this.href)
                    return this.href == url;
            }).parentsUntil(".nav-sidebar > .nav-treeview").addClass('menu-open').prev('a').addClass('active');
        });
    </script>
</body>

</html>
