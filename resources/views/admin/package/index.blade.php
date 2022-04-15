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
        <div class="d-flex justify-content-end">
            <a type="button" href="{{route('admin.package.create')}}" class="btn btn-primary"><i class="fas fa-plus"></i> Add package</a>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Max File Upload</th>
                                <th>Max File Size</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($packages as $package)
                                <tr>
                                    <td>{{$package->id}}</td>
                                    <td>{{$package->name}}</td>
                                    <td>{{$package->max_file_upload}}</td>
                                    <td>{{$package->max_file_size}}</td>
                                    <td>
                                        <a class="btn btn-primary" href="/admin/package/edit/{{$package->id}}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="delete btn btn-danger" onclick="showModal({{ $package->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
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
        {{ $packages->links() }}
    </section>
    <!-- /.content -->
@stop
@section('modal')
    <div class="modal" tabindex="-1" role="dialog" id="modal_confirm_delete_package">
        <form action="{{url('admin/package/delete')}}" method="get">
            <input type="hidden" name="id" id="_id" value="">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Modal body text goes here.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Delete</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop
@section('js')
   <script type="text/javascript">
        function showModal(id) {
            $('#modal_confirm_delete_package').modal('show');
            $('#_id').val(id);
        }
    </script>
@stop

