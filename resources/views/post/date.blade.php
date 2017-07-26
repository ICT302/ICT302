@extends('layouts.app')

@section('content')

<div class="container">

	<div class="row">

		<div class="col-md-8">

			<div class="panel panel-default">

				<div class="panel-heading">
                	<h3 class="panel-title">Posts View By Year {{ $year }} @if($month > 0), Month {{ $month }}@endif @if($day > 0), Day {{ $day }}@endif
                	<a class="btn btn-default return-button"  href="{{ URL::previous() }}">Return</a>
                	</h3>
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
              	
              		@foreach($date_posts as $post)
              			<div class="row post">
	                        <article class="col-xs-12">
	                          <h2><a href="{{ url('post',$post->id) }}">{{ ucwords($post->title) }}</a></h2>
	                          <p><i class="fa fa-clock-o" aria-hidden="true"></i>
	                            Posted on {{ $post->datetime->format('F d, Y') }} at {{ $post->datetime->format('g:i A') }}</p>

                            @if(!$post->datetime->eq($post->updated_at))
	                          <p><i class="fa fa-pencil" aria-hidden="true"></i>
	                            Updated on {{ $post->updated_at->format('F d, Y') }} at {{ $post->updated_at->format('g:i A') }}</p>
                            @endif

	                           <p class="author"><i class="fa fa-user-o"></i> Author: {{ ucwords($post->user->first_name) }} {{ ucwords($post->user->last_name) }}</p>
	                           <br>	
	                          <p>
	                                {{ substr($post->content,0,250) }}@if(strlen($post->content) >= 100)...@endif
	                          </p>
	                          <a class="btn btn-primary read-more" href="{{ url('post', $post->id) }}">Read More <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
	                          <div class="clear"></div>
	                        </article>
                    	</div>
                    	<hr>
					@endforeach

              	</div>
			</div>
		</div>

		<div class="col-md-4">

			<div class="panel panel-default col-xs-12">
	          	<div class="panel-body">
	              	<form method="POST" action="{{ url('post/search') }}" id="search-form">
	                {{ csrf_field() }}

		              	<label for="search" class="col-md-12 control-label">Search Post</label>

		              	<div class="col-md-12 search-row">
		              		<input type="text" class="form-control" name="search">
		              		<i class="fa fa-search search-btn" aria-hidden="true" onClick='$("#search-form").submit();'></i>
		              	</div>
	              	</form>
	          	</div>
	     	</div>

			<div class="calendar-panel panel panel-default col-xs-12">
	          	<div class="panel-body">
	              	<div id="post-calendar"></div>
	          	</div>
	     	</div>

		</div>
	</div>
</div>

@endsection

@section('footer')
<script type="text/javascript">
	$(document).ready(function() {

		// check if this day has an event before
	    function dateHasEvent(date) {
	        var allEvents = [];
	        allEvents = $('#post-calendar').fullCalendar('clientEvents');
	        var event = $.grep(allEvents, function (v) {
	            return +v.start === +date;
	        });
	        return event.length > 0;
	    }


    	$('#post-calendar').fullCalendar({
    		header: {
			   left: '',
			   center: 'prev title next',
			   right: 'listWeek,month'
			  },
		  	buttonText: {
		  		list: 	'week'
		  	},			  
		  	titleFormat: 'MMM YYYY',
		  	height: 'auto',
		  	timezone: 'UTC',
	  	 	dayClick: function(date, jsEvent, view) {
  	 			if (dateHasEvent(date)) {
          			var year = date.year();
  					var month = date.month() + 1;
  					var day = date.date();
  					window.location.replace("{{ url('post/date') }}/" + year + "/" + month + "/" + day);
			    }
		    },
	  	 	events: function( start, end, timezpne, callback ) {
	  	 		var year = end.year();
	  	 		var month = end.month();

	  	 		new_url  = '{{ url("post/calendar/") }}/' + year + '/' + month;
				$.ajax({
                    url: new_url,
                    dataType: 'json',
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}'
					},
                    success: function( response ) {
                    	user_events = response;
                        callback(response);
                    }
                })
	  	 	},
			eventRender: function (event, element) {
			    var dataToFind = moment(event.start).format('YYYY-MM-DD');
			    $("td[data-date='"+dataToFind+"']").addClass('eventDay');
			}	  	 	
    	});

		$( "#post-calendar" ).on("click",".fc-listWeek-button",function(event){
			$("#post-calendar").fullCalendar('option','titleFormat','D MMM YYYY');
			$('.fc-listWeek-button').hide();
			$('.fc-month-button').show();
		});
		$( "#post-calendar" ).on("click",".fc-month-button",function(event){
			$("#post-calendar").fullCalendar('option','titleFormat','MMM YYYY');
			$('.fc-month-button').hide();
			$('.fc-listWeek-button').show();
		});	
		
        //post calendar month on click
        $( "#post-calendar" ).on("click",".fc-center h2",function(event){
    		var date = $("#post-calendar").fullCalendar('getDate');
  			var year = date.year();
  			var month = date.month() + 1;
  			window.location.replace("{{ url('post/date') }}/" + year + "/" + month);
        });


	});
</script>
@endsection