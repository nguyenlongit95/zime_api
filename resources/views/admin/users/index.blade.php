@extends('admin.layouts.app')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">{{$title}}</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- Main content -->
    <section class="content">
        <nav class="navbar navbar-light bg-light">
            <form class="form-inline" method="get" action="{{url('/admin/user')}}">
                <input class="form-control mr-sm-2" value="@if(isset($param['email'])) {{$param['email']}} @endif"  name="email" type="search" placeholder="Email" aria-label="Email">
                <input class="form-control mr-sm-2" value="@if(isset($param['phone'])) {{$param['phone']}} @endif" name="phone" type="search" placeholder="Phone" aria-label="Phone">
                <select class="form-control mr-sm-2" name="package_id">
                    <option value="0">Package</option>
                    @foreach($packages as $package)
                        <option value="{{$package->id}}" @if(isset($param['package_id'])) {{$package->id==$param['package_id']?'selected':''}} @endif>{{$package->name}}</option>
                    @endforeach
                </select>
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                @csrf
            </form>
        </nav>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Phone</th>
                                <th>Package</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{$user->id}}</td>
                                    <td>{{$user->email}}</td>
                                    <td>{{$user->address}}</td>
                                    <td>{{$user->phone}}</td>
                                    <td>{{$user->package->name}}</td>
                                    <td>
                                        <a class="btn btn-primary" href="/admin/user/detail/{{$user->id}}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
        @if(!empty($users))
        {!! $users->appends($_GET)->links() !!}
        @endif
    </section>
    <!-- /.content -->
@stop


