<!DOCTYPE html>
<html>
<head>
    @include('admin.partials.header')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Navbar -->
@include('admin.partials.navbar')
<!-- /.navbar -->

    <!-- Main Sidebar Container -->
@include('admin.partials.sidebar')

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

@include('admin.partials.footer')
@yield('modal')
@yield('js')
</body>
</html>
