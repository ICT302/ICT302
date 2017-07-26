@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Register</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                            <label for="first_name" class="col-md-4 control-label">First Name</label>

                            <div class="col-md-6">
                                <input id="first_name" type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" required autofocus>

                                @if ($errors->has('first_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                            <label for="last_name" class="col-md-4 control-label">Last Name</label>

                            <div class="col-md-6">
                                <input id="last_name" type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" required autofocus>

                                @if ($errors->has('last_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('dob') ? ' has-error' : '' }}">
                            <label for="dob" class="col-md-4 control-label">Date of Birth</label>

                            <div class="col-md-6">
                                <input id="dob" type="date" class="form-control" name="dob" value="{{ old('dob') }}" required autofocus>

                                @if ($errors->has('dob'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('dob') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <hr>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>                                

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email_confirmation') ? ' has-error' : '' }}">
                            <label for="email_confirmation" class="col-md-4 control-label">Confirm E-Mail</label>

                            <div class="col-md-6">
                                <input id="email_confirmation" type="email" class="form-control" name="email_confirmation" value="{{ old('email_confirmation') }}" required>

                                @if ($errors->has('email_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <hr>

                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <label for="username" class="col-md-4 control-label">Username</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required>
                                <span class="hint">Username is your Student ID</span>

                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif

                                <div id="password-meter" style="display: none;">
                                    <div class="arrow"></div>
                                    <div class="password-strength">
                                        <p><strong>Password strength: </strong><span id="password-rating"></span></p>
                                        <div class="meter"><span id="password-bar" class="default"></span></div>
                                    </div>
                                    <ul class="dynamic-list">
                                        <li id="pwdLength" class="default"><i class="fa"></i> Use at least 8 characters long or more</li>
                                        <li id="pwdHacker" class="default"><i class="fa"></i> Not in hackers list</li>
                                        <li id="pwdDict" class="default"><i class="fa"></i> Not a dictonary word</li>
                                        <li id="pwdLower" class="default"><i class="fa"></i> Add a lowercase character</li>
                                        <li id="pwdUpper" class="default"><i class="fa"></i> Add an uppercase character</li>
                                        <li id="pwdNumber" class="default"><i class="fa"></i> Add a number</li>
                                        <li id="pwdSpecial" class="default"><i class="fa"></i> Add a special character (e.g., ?, %, &)</li>
                                    </ul>
                                    <div class="password-entropy" style="display: none">
                                        <p>The password you have chosen is <span id="entropy-rating" class="loading"></span> and may take a hacker <span id="entropy-duration" class="loading"></span> to guess.</p>
                                    </div>
                                    <div class="password-entropy-suggestion" style="display: none">
                                        <p>Follow the above simple recommendations will help making your password take at least <span id="entropy-duration-suggestion" class="loading"></span> to guess.</p>
                                    </div>                                    
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('footer')
<script type="text/javascript">

    var url = '{{url("passwordCheck")}}';
    var token = '{{ csrf_token() }}';

</script>
<script src="{{ asset('js/password-meter.js') }}"></script>
<script src="{{ asset('js/password-dynamic.js') }}"></script>
@endsection