@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">All Admins
                    <a class="btn btn-default new-admin" href="{{ url('admin/create') }}">Create Admin</a>
                    <div class="clear"></div>
                </div>
                <div class="panel-body">

                    @if(Session::has('success'))
                    <div class="alert alert-success">
                    <strong>Success!</strong> {{ Session::get('success') }}
                    </div>      
                    @endif

                    @if(Session::has('error'))
                    <div class="alert alert-danger">
                    <strong>Error!</strong> {{ Session::get('error') }}
                    </div>      
                    @endif

                    <!-- SHOW ALL ADMINS -->
                    <div class="table-responsive">
                      <table class="table table-bordered dataTable" id="admin-table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>DOB</th>
                                <th>Email</th>
                                <th>Username</th>
                                @if(Auth::user()->isSuperAdmin())
                                    <th>Password</th>
                                    <th>Hash Password</th>
                                @endif
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($admins as $admin)
                            <tr>
                                <td>{{ ucwords($admin->first_name) }} {{ ucwords($admin->last_name) }}</td>
                                <td>{{$admin->dob->format('d F Y')}}</td>
                                <td>{{$admin->email}}</td>
                                <td>{{$admin->username}}</td>
                                @if(Auth::user()->isSuperAdmin())
                                    <td><a class="pwd-link" data-toggle="modal" data-target="#pwdModal" href="#" data-pwd="{{ $admin->plain_password }}">Show Password</a></td>
                                    <td><a class="pwd-link" data-toggle="modal" data-target="#pwdModal" href="#" data-pwd="{{ $admin->password }}">Show Password</a></td>
                                @endif
                                <td>{{$admin->role}}</td>
                               
                                <td>
                                @if(Auth::user()->isSuperAdmin())
                                    <a href="{{ url('admin/'.$admin->id.'/edit') }}">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                        Edit
                                    </a> 
                                @endif
                                @if(!$admin->isSuperAdmin())
                                    <a class="delete-admin" href="#" data-id="{{$admin->id}}">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                        Delete
                                    </a>                         
                                @endif            
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                      </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="pwdModal" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Show Password</h4>
        </div>
        <div class="modal-body">
          <p>Some text in the modal.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>      
    </div>
</div>

@endsection


@section('footer')
<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#admin-table').DataTable();

        $( ".pwd-link" ).on("click",function(){            
          $('#pwdModal .modal-body > p').html($(this).attr('data-pwd'));
        });



        //delete admin link
        $( ".delete-admin" ).on("click",function(event){
            event.preventDefault();
            var row = $(this).parents('tr');
            var admin_id = $(this).attr('data-id');
            var result = confirm("Are you sure you want to delete this admin? This action cannot be undo.");
            if(result){
                //ajax
               $.ajax({
                    type: 'POST',
                    url: '{{url("admin")}}/'+admin_id,
                    data: { 
                        '_method': 'delete', 
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function($delete){
                        if($delete['result'] == "true")
                        {
                            //remove deleted row and show success noti
                            //remove the row and show success notification
                            table.row(row).remove().draw();
                            $('.panel-body').prepend('<div class="alert alert-success"><strong>Success!</strong> '+$delete['message']+'</div>');
                        }
                        else{
                            //show error noti
                            $('.panel-body').prepend('<div class="alert alert-danger"><strong>Error!</strong> '+$delete['message']+'</div>');
                        }                    
                    }
                });                 
            }
        });
    } );
</script>

@endsection
