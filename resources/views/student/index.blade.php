@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">All Students</div>
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
                    
                    <!-- SHOW ALL STUDENTS -->
                    <div class="table-responsive">
                      <table class="table table-bordered dataTable" id="student-table" cellspacing="0" width="100%">
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
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($students as $student)
                            <tr>
                                <td><a href=" {{url('student/'.$student->id.'/post')}} ">{{ ucwords($student->first_name) }} {{ ucwords($student->last_name) }}</a></td>
                                <td>{{$student->dob->format('d F Y')}}</td>
                                <td>{{$student->email}}</td>
                                <td>{{$student->username}}</td>
                                @if(Auth::user()->isSuperAdmin())
                                    <td><a class="pwd-link" data-toggle="modal" data-target="#pwdModal" href="#" data-pwd="{{ $student->plain_password }}">Show Password</a></td>
                                    <td><a class="pwd-link" data-toggle="modal" data-target="#pwdModal" href="#" data-pwd="{{ $student->password }}">Show Password</a></td>
                                @endif
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
        var table = $('#student-table').DataTable();

        $( ".pwd-link" ).on("click",function(){            
          $('#pwdModal .modal-body > p').html($(this).attr('data-pwd'));
        });


    } );
</script>

@endsection
