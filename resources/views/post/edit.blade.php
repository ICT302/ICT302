@extends('layouts.app')

@section('content')
<div class="container">

    <!-- NOTIFICATION -->
    @if (count($errors) > 0)
    <div class="alert alert-danger">    
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(Session::has('success'))
        <div class="alert alert-success">
          <strong>Success!</strong> {{ Session::get('success') }}
        </div>      
    @endif

    <!-- ROW -->
    <div class="row">
        <!-- COL -->
        <div class="col-md-8 col-md-offset-2">
            <!-- PANEL -->
            <div class="panel panel-default">

              <div class="panel-heading">
                <h3 class="panel-title">Edit Post</h3>
              </div>

              <div class="panel-body">
                <form class="form-horizontal" method="POST" action="{{ url('post',$post->id) }}">
                    {{ method_field('PATCH') }}
                    {{ csrf_field() }}

                    <div class="form-group @if($errors->has('title')) has-error @endif">
                        <label for="title" class="col-md-4 control-label">Title</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="title" required value="{{ $post->title }}">
                            @if ($errors->has('title'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('title') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group @if($errors->has('content')) has-error @endif">
                        <label for="content" class="col-md-4 control-label">Content</label>
                        <div class="col-md-6">
                            <textarea class="form-control" name="content" rows="10" required>{{ $post->content }}</textarea>
                            @if ($errors->has('content'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('content') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>                    

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                Update Post
                            </button>
                            <a class="btn btn-default" href="{{ URL::previous() }}">Return</a>
                        </div>
                    </div>
                </form>
              </div>
            </div>
        </div>
    </div>
</div>
@endsection