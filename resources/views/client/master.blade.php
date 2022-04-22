<!DOCTYPE html>
<html>
<head>
    @include('client.partials.header')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Navbar -->
@include('client.partials.navbar')
<!-- /.navbar -->

    <!-- Main Sidebar Container -->
@include('client.partials.sidebar')

<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        @yield('alert')
        @yield('content')
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

@include('client.partials.footer')
<script src="{{ asset('js/app.js') }}" defer></script>
@yield('modal')
@yield('js')
</body>
</html>
