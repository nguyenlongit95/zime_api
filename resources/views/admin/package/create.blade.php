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
        <form role="form" method="post" action="{{url('admin/package/create')}}">
            <div class="card-body">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" value="{{old('name')}}" class="form-control @error('name') is-invalid @enderror" placeholder="Enter name">
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Total File Upload</label>
                    <input type="text" name="max_file_upload" value="{{old('max_file_upload')}}" class="form-control @error('max_file_upload') is-invalid @enderror"  placeholder="Enter total file upload">
                    @error('max_file_upload')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Max File Size</label>
                    <input type="text" name="max_file_size" value="{{old('max_file_size')}}" class="form-control @error('max_file_size') is-invalid @enderror"  placeholder="Enter max file size">
                    @error('max_file_size')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer d-flex justify-content-end">
                <button type="submit" class="btn btn-primary ">Create</button>
            </div>
            @csrf
        </form>
    </section>
    <!-- /.content -->
@stop
