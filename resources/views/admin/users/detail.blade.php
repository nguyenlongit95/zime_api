@extends('admin.layouts.app')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">{{$title}}: {{$user->email}}</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- Main content -->
    <section class="content">
        @if(Session::has('success'))
            <div class="alert alert-success text-center">
                {{Session::get('success')}}
            </div>
        @endif
        <div class="row">
            <div class="col-8">
                <form role="form" method="post">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" readonly name="phone" value="{{ $user->phone }}" class="form-control @error('name') is-invalid @enderror" placeholder="Enter Phone">
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" readonly name="address" value="{{ $user->address }}" class="form-control @error('address') is-invalid @enderror" placeholder="Enter Address">
                            @error('address')
                            <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" readonly name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter Password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                            @enderror
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer d-flex justify-content-end">
                        <a href="/admin/user/edit/{{$user->id}}" class="btn btn-primary ">Edit</a>
                    </div>
                    @csrf
                </form>
            </div>
            <div class="col-md-4" style="margin-top: 10px">
                <div class="info-box mb-3 bg-warning">
                    <span class="info-box-icon"><i class="nav-icon fas fa-book"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Package Name</span>
                        @if(empty($package))
                            <span class="info-box-number">No package</span>
                        @else
                            <span class="info-box-number">{{$package->name}}</span>
                        @endif
                    </div>
                </div>

                <div class="info-box mb-3 bg-success">
                    <span class="info-box-icon"><i class="fas fa-file"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Files</span>
                        @if(empty($package))
                            <span class="info-box-number">No file upload</span>
                        @else
                            <span class="info-box-number">{{$totalFiles}}/{{$package->max_file_upload}}</span>
                        @endif
                    </div>
                </div>

                <div class="info-box mb-3 bg-danger">
                    <span class="info-box-icon"><i class="fas fa-edit"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Time last upload</span>
                        @if(empty($totalFiles))
                            <span class="info-box-number">No file upload</span>
                        @else
                            <span class="info-box-number">{{$lastFileUpload->last_time_upload->format("d/m/Y")}}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">List Files</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($files as $file)
                                        <tr>
                                            <td>{{$file->id}}</td>
                                            <td>{{$file->name}}</td>
                                            <td>
                                                <button class="btn btn-primary" onclick="showModal({{$file->id}})">
                                                    <i class="fas fa-eye"></i>
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
            </div>
        </div>
            {{ $files->links() }}
    </section>
    <!-- /.content -->
@stop
@section('modal')
    <div class="modal" tabindex="-1" role="dialog" id="modal_show_detail_file">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">File Detail</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <div class="form-group">
                            <label>File name</label>
                            <h5 id="file_name"></h5>
                        </div>
                        <div class="form-group">
                            <label>File size</label>
                            <h5 id="file_size">ABC</h5>
                        </div>
                        <div class="form-group">
                            <label>Time Upload</label>
                            <h5 id="time_upload">ABC</h5>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script type="text/javascript">
        function showModal(id) {
            $('#modal_show_detail_file').modal('show');
            $.ajax({
                url: '{{ url('admin/user/show-file') }}',
                type : 'get',
                data : {
                    id : id
                },
                success : function (response) {
                    if (response.code === 200) {
                        $('#file_name').text(response.data.name);
                        $('#file_size').text(response.data.file_size);
                        $('#time_upload').text(response.data.updated_at);
                    }
                }
            })
        }
    </script>
@stop

