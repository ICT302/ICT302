@extends('layouts.app')

@section('content')
<div class="container">

    <!-- NOTIFICATION -->
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


			  <div class="panel-body post-list">
          <div class="row post">
              <article class="col-xs-12">
                <h2>{{ ucwords($post->title) }}</h2>
                <p><i class="fa fa-clock-o" aria-hidden="true"></i>
                  Posted on {{ $post->datetime->format('F d, Y') }} at {{ $post->datetime->format('g:i A') }}</p>

                @if(!$post->datetime->eq($post->updated_at))
                <p><i class="fa fa-pencil" aria-hidden="true"></i>
                  Updated on {{ $post->updated_at->format('F d, Y') }} at {{ $post->updated_at->format('g:i A') }}</p>
                @endif   
                                  
                <p class="author"><i class="fa fa-user-o"></i> Author: {{ ucwords($post->user->first_name) }} {{ ucwords($post->user->last_name)}}</p>     

                <hr>
                <p>{{ $post->content }}</p>
                <hr>

                @if($post->isAuthor(Auth::id()))
                <a class="btn btn-primary" href="{{ url('post/'.$post->id.'/edit') }}">Edit Post</a>
                <form class="form-horizontal delete-form" method="POST" action="{{ url('post',$post->id) }}">
                  {{ method_field('DELETE') }}
                  {{ csrf_field() }}
                  <div class="form-group">
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-danger">
                            Delete
                        </button>
                    </div>
                  </div>
                </form>
                @endif

                @if(Auth::user()->isStudent())
                <a class="btn btn-default" href="{{ url('post') }}">Return</a>
                @else
                <a class="btn btn-default" href="{{ URL::previous() }}">Return</a>
                @endif
              </article>
          </div>

			  </div>
			</div>
	    </div>
    </div>
</div>
@endsection


@section('footer')
<script type="text/javascript">
  $( document ).ready(function() {
    $('.delete-form').submit(function() {
      var result = confirm("Are you sure you want to delete this record? This action cannot be undo.");
      return result;     
    });
  });
</script>
@endsection