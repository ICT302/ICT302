@extends('layouts.app')

@section('content')
<div class="container">
    <!-- ROW -->
    <div class="row">
        <!-- COL -->
        <div class="col-md-8 col-md-offset-2">
            <!-- PANEL -->
            <div class="panel panel-default">

              <div class="panel-heading">
                <h3 class="panel-title">Create New Post</h3>
              </div>

              <div class="panel-body">
                <form class="form-horizontal" method="POST" action="{{ url('post') }}">
                    {{ csrf_field() }}

                    <div class="form-group @if($errors->has('title')) has-error @endif">
                        <label for="title" class="col-md-4 control-label">Title</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="title" required value="{{ old('title') }}">
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
                            <textarea class="form-control" name="content" rows="10" required>{{ old('content') }}</textarea>
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
                                Create Post
                            </button>
                            <a class="btn btn-default" href="{{ url('post') }}">Return</a>
                        </div>

                    </div>
                </form>
              </div>
            </div>
        </div>
    </div>
</div>
@endsection